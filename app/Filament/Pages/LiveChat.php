<?php

namespace App\Filament\Pages;

use App\Models\LiveChat as ChatSession;
use App\Models\LiveChatMessage;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class LiveChat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Customer Support';
    protected static ?string $navigationLabel = 'Chat Pelanggan';
    protected static ?string $title = 'Live Chat dengan Pelanggan';
    protected static string $view = 'filament.pages.live-chat';

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
        $this->activeChats = ChatSession::where('status', 'active')->latest()->get();
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
            $fastapiUrl = env('FASTAPI_CHATBOT_URL', 'http://127.0.0.1:8000/chatbot');
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
