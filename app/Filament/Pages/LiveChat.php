<?php

namespace App\Filament\Pages;

use App\Models\LiveChat as ChatSession;
use App\Models\LiveChatMessage;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class LiveChat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Layanan Pelanggan';
    protected static ?string $navigationLabel = 'Obrolan Pelanggan';
    protected static ?string $title = 'Obrolan Pelanggan (Live Chat)';
    protected static string $view = 'filament.pages.live-chat';

    public static function getNavigationBadge(): ?string
    {
        $count = ChatSession::where('status', 'active')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public $activeChats = [];
    public $selectedChatId = null;
    public $messages = [];
    public $newMessage = '';

    public function mount()
    {
        $this->loadActiveChats();
    }

    public function loadActiveChats()
    {
        $this->activeChats = ChatSession::where('status', 'active')
            ->with(['messages' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->latest()
            ->get()
            ->map(function ($chat) {
                $chat->unread_count = $chat->messages->where('sender', 'user')->where('is_read', false)->count();
                $chat->latest_message = $chat->messages->first()?->message ?? '';
                return $chat;
            });
            
        if ($this->selectedChatId) {
            $this->loadMessages($this->selectedChatId);
        }
    }

    public function selectChat($chatId)
    {
        $this->selectedChatId = $chatId;
        $this->loadMessages($chatId);
    }

    public function loadMessages($chatId)
    {
        // Tandai pesan dari pelanggan sudah dibaca oleh admin
        LiveChatMessage::where('live_chat_id', $chatId)
            ->where('sender', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->messages = LiveChatMessage::where('live_chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '' || !$this->selectedChatId) {
            return;
        }

        LiveChatMessage::create([
            'live_chat_id' => $this->selectedChatId,
            'sender' => 'admin',
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages($this->selectedChatId);
    }

    public function endSession()
    {
        if (!$this->selectedChatId) return;

        $chat = ChatSession::find($this->selectedChatId);
        if ($chat) {
            $chat->update(['status' => 'closed']);

            // Beri tahu FastAPI bahwa admin sudah selesai (ini penting untuk reset state FastAPI)
            $fastapiUrl = config('services.chatbot.url', env('FASTAPI_CHATBOT_URL', 'http://127.0.0.1:8000/chatbot'));
            try {
                Http::timeout(5)->post($fastapiUrl, [
                    'message' => 'selesai', // "selesai" atau "_end_admin_" trigger kembali ke bot (lihat api.py bagian exit_keywords atau post_admin_offer)
                    'user_id' => $chat->user_id,
                ]);
            } catch (\Exception $e) {
                // Abaikan jika FastAPI error
            }
            
            $this->selectedChatId = null;
            $this->messages = [];
            $this->loadActiveChats();
        }
    }
}
