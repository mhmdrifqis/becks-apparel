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

        // 2. Jika tidak ada sesi aktif, kirim ke Gemini API
        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key Gemini belum dikonfigurasi oleh Admin.',
            ], 500);
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

        // Siapkan history chat untuk context
        $history = $request->input('history', []);
        $contents = [];

        foreach ($history as $h) {
            $sender = $h['sender'] ?? 'user';
            $role = ($sender === 'bot') ? 'model' : 'user';
            $text = trim($h['message'] ?? '');
            
            if (empty($text)) continue;

            // Pastikan tidak ada role yang berturut-turut sama (Gemini API requirement)
            $lastIndex = count($contents) - 1;
            if ($lastIndex >= 0 && $contents[$lastIndex]['role'] === $role) {
                // Merge text if role is the same
                $contents[$lastIndex]['parts'][0]['text'] .= "\n" . $text;
            } else {
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $text]]
                ];
            }
        }

        // Jika history kosong atau pesan terakhir bukan dari user (walau jarang terjadi), pastikan pesan saat ini masuk
        $lastRole = count($contents) > 0 ? $contents[count($contents)-1]['role'] : null;
        if (count($contents) === 0 || ($request->message !== end($history)['message'] ?? null)) {
            if ($lastRole === 'user') {
                $contents[count($contents)-1]['parts'][0]['text'] .= "\n" . $request->message;
            } else {
                $contents[] = [
                    'role' => 'user',
                    'parts' => [['text' => $request->message]]
                ];
            }
        }

        // System Instruction agar AI berperilaku sebagai BecksBot
        // System Instruction agar AI berperilaku sebagai BecksBot dengan data asli
        // System Instruction agar AI berperilaku sebagai BecksBot dengan data asli
        $systemInstruction = [
            'role' => 'user',
            'parts' => [[
                'text' => "Kamu adalah BecksBot, asisten Customer Service virtual dari 'Becks Apparel'.\n" .
                          "Gaya bahasamu ramah, asik, profesional, dan menggunakan bahasa gaul Indonesia sehari-hari (pakai sapaan 'Kak').\n\n" .
                          
                          "=== PRODUK & DAFTAR HARGA ===\n" .
                          "[JERSEY]\n" .
                          "1. Paket A (Rp 90.000): Jersey & Celana Non-printing, Logo/Sponsor/Nameset DTF.\n" .
                          "2. Paket B (Rp 110.000): Badan Non-printing, Lengan Printing, DTF.\n" .
                          "3. Paket C (Rp 130.000): Jersey Full Printing, Celana Non-printing.\n" .
                          "4. Paket D (Rp 160.000): Jersey & Celana Full Printing.\n" .
                          "5. Paket E (Rp 170.000): Full Printing Premium, Logo/Sponsor/Nameset DTF.\n" .
                          "Tersedia puluhan bahan gratis (Milano, Benzema, Smash, N-Tech, Wafel, dll).\n\n" .
                          
                          "[JAKET & LAINNYA]\n" .
                          "- Jaket Paket A (Rp 170.000): Jaket Full Printing (Bahan Lotto/Diadora).\n" .
                          "- Jaket Paket B (Rp 155.000): Jaket Kombinasi Printing + Bahan.\n" .
                          "- Jaket Paket C (Rp 250.000): Setelan Jaket Full Printing + Celana Training.\n" .
                          "- T-Shirt Cotton 24s (Rp 80.000), 30s (Rp 60.000) Sablon DTF.\n" .
                          "- Kemeja Drill (Rp 80.000) bahan Verlando CP/Maryland.\n\n" .
                          
                          "=== FITUR ONLINE CUSTOMIZER 3D ===\n" .
                          "- Kami memiliki fitur 'Online Customizer' yang super canggih di website ini.\n" .
                          "- Pelanggan bisa mendesain jersey dari nol langsung lewat HP/Laptop (bisa ganti warna lengan, tambah motif kerah, pasang logo, nama, hingga sponsor depan/belakang).\n" .
                          "- Desain yang dibuat bisa diputar/dilihat dalam bentuk 3D, lalu bisa langsung disimpan dan dipesan (checkout).\n" .
                          "- Arahkan pelanggan untuk klik tombol 'Customizer' di menu atas atau klik 'Desain Sekarang' di halaman katalog jika mereka ingin mendesain sendiri.\n\n" .

                          "=== UPGRADE & BIAYA TAMBAHAN ===\n" .
                          "- Logo: Rubber (+20rb), Semiwoven (+25rb), Bordir (+30rb).\n" .
                          "- Fitur: Tangan Panjang (+20rb), Tangan Raglan (+15rb), Kerah Kemeja/Wangki (+20rb).\n" .
                          "- Surcharge Ukuran Besar (Jumbo): Jersey nambah Rp 5.000 tiap naik 1 size di atas XL (XXL +5rb, XXXL +10rb, dst). T-Shirt/Kemeja nambah Rp 10.000 tiap naik 1 size di atas XL.\n\n" .

                          "=== ATURAN PENTING & HANDOVER ===\n" .
                          "1. Jawab pertanyaan dengan singkat, padat, ramah, dan solutif. Gunakan emoji secukupnya.\n" .
                          "2. Jangan memberikan janji diskon di luar wewenangmu.\n" .
                          "3. JIKA PENGGUNA MARAH, komplain, butuh negosiasi harga, atau secara eksplisit meminta berbicara dengan Admin / CS Manusia, BALAS DENGAN HANYA SATU KATA INI: [HANDOVER]\n" .
                          "Jangan tambahkan kata lain atau kalimat apapun selain [HANDOVER] jika kondisi tersebut terpenuhi."
            ]]
        ];

        try {
            $response = Http::timeout(15)->post($url, [
                'system_instruction' => $systemInstruction,
                'contents' => $contents,
            ]);

            $data = $response->json();

            if (!isset($data['candidates']) || count($data['candidates']) === 0) {
                // Log detail API error if possible
                $errDetail = isset($data['error']['message']) ? $data['error']['message'] : 'Invalid response';
                throw new \Exception('Gemini API Error: ' . $errDetail);
            }

            $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Cek apakah ada trigger [HANDOVER]
            if (str_contains(strtoupper($replyText), '[HANDOVER]')) {
                // Buat sesi LiveChat baru
                LiveChat::create([
                    'user_id' => $userId,
                    'user_name' => auth()->check() ? auth()->user()->name : 'Guest',
                    'status' => 'active'
                ]);

                return response()->json([
                    'status' => 'handover',
                    'message' => 'Saya akan segera menghubungkan Kakak dengan tim CS Becks Apparel ya! Mohon tunggu sebentar...',
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => trim($replyText),
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot API Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf Kak, asisten Becks saat ini sedang mengalami gangguan teknis. Silakan coba beberapa saat lagi.',
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

        $query = $chat->messages()->where('id', '>', $lastId);
        
        // Hanya ambil pesan admin jika bukan permintaan load riwayat penuh (all=1)
        if (!$request->query('all')) {
            $query->where('sender', 'admin');
        }

        $messages = $query->orderBy('id', 'asc')->get();

        return response()->json([
            'messages' => $messages,
            'status' => $chat->status, // 'active' atau 'closed'
        ]);
    }
}
