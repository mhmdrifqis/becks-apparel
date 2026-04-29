<?php

namespace App\Http\Controllers;

use App\Models\LiveChat;
use App\Models\LiveChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function handleChat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userId = auth()->check() ? (string) auth()->id() : 'guest_' . session()->getId();
        
        // 1. Cek apakah ada sesi LiveChat aktif untuk user ini
        $activeChat = LiveChat::where('user_id', $userId)->where('status', 'active')->first();

        if ($activeChat) {
            // Jika ada sesi admin aktif, SIMPAN pesan ke database, JANGAN kirim ke FastAPI
            LiveChatMessage::create([
                'live_chat_id' => $activeChat->id,
                'sender' => 'user',
                'message' => $request->message,
            ]);

            return response()->json([
                'status' => 'admin_mode',
                'message' => ''
            ]);
        }

        // 2. Jika tidak ada sesi aktif, proxy ke FastAPI
        $fastapiUrl = env('FASTAPI_CHATBOT_URL', 'http://127.0.0.1:8000/chatbot');

        try {
            $response = Http::timeout(10)->post($fastapiUrl, [
                'message' => $request->message,
                'user_id' => $userId,
            ]);

            $data = $response->json();

            // 3. Jika FastAPI mengembalikan 'handover', berarti user baru saja menyetujui admin
            if (isset($data['status']) && $data['status'] === 'handover') {
                // Buat sesi LiveChat baru
                LiveChat::create([
                    'user_id' => $userId,
                    'user_name' => auth()->check() ? auth()->user()->name : 'Guest',
                    'status' => 'active'
                ]);
            }

            return response()->json($data, $response->status());

        } catch (\Exception $e) {
            Log::error('Chatbot API Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, asisten Becks saat ini sedang offline atau sibuk. Silakan coba beberapa saat lagi.',
            ], 500);
        }
    }

    public function pollMessages(Request $request)
    {
        $userId = auth()->check() ? (string) auth()->id() : 'guest_' . session()->getId();
        $lastId = $request->query('last_id', 0);

        // Cari chat aktif terakhir
        $activeChat = LiveChat::where('user_id', $userId)->where('status', 'active')->latest()->first();

        // Cari chat yang baru saja ditutup jika tidak ada yang aktif
        $recentlyClosed = null;
        if (!$activeChat) {
            $recentlyClosed = LiveChat::where('user_id', $userId)->where('status', 'closed')->latest()->first();
        }

        $chat = $activeChat ?? $recentlyClosed;

        if (!$chat) {
            return response()->json(['messages' => [], 'status' => 'no_chat']);
        }

        // Ambil pesan dari admin yang lebih baru dari last_id
        $messages = $chat->messages()
            ->where('sender', 'admin')
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'status' => $chat->status, // 'active' atau 'closed'
        ]);
    }
}
