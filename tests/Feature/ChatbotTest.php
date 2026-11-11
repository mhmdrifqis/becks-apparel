<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LiveChat;
use App\Models\LiveChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_merespons_pertanyaan_pengguna_dengan_mock_gemini(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo Kak! Paket C harganya Rp 130.000 dengan jersey full printing.']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $response = $this->postJson(route('chatbot.handle'), [
            'message' => 'Berapa harga jersey Paket C?',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Halo Kak! Paket C harganya Rp 130.000 dengan jersey full printing.',
        ]);
    }

    public function test_chatbot_memicu_handover_dan_membuat_sesi_live_chat(): void
    {
        $user = User::factory()->create();

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => '[HANDOVER]']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $response = $this->actingAs($user)->postJson(route('chatbot.handle'), [
            'message' => 'Saya ingin berbicara dengan CS Admin langsung!',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'handover',
        ]);

        $this->assertDatabaseHas('live_chats', [
            'user_id' => (string) $user->id,
            'status' => 'active',
        ]);
    }

    public function test_mode_live_chat_aktif_menyimpan_pesan_ke_database(): void
    {
        $user = User::factory()->create();
        $liveChat = LiveChat::factory()->create([
            'user_id' => (string) $user->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->postJson(route('chatbot.handle'), [
            'message' => 'Halo min, tolong cek status pesanan saya.',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'admin_mode']);

        $this->assertDatabaseHas('live_chat_messages', [
            'live_chat_id' => $liveChat->id,
            'sender' => 'user',
            'message' => 'Halo min, tolong cek status pesanan saya.',
        ]);
    }

    public function test_pengguna_bisa_polling_pesan_dan_otomatis_tandai_terbaca(): void
    {
        $user = User::factory()->create();
        $liveChat = LiveChat::factory()->create([
            'user_id' => (string) $user->id,
            'status' => 'active',
        ]);

        $adminMessage = LiveChatMessage::factory()->create([
            'live_chat_id' => $liveChat->id,
            'sender' => 'admin',
            'message' => 'Baik Kak, pesanan sedang dalam proses cetak ya.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->getJson(route('chatbot.poll', ['last_id' => 0]));

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Baik Kak, pesanan sedang dalam proses cetak ya.']);
        $this->assertTrue((bool) $adminMessage->fresh()->is_read);
    }

    public function test_kirim_pesan_kosong_ke_chatbot_ditolak(): void
    {
        $response = $this->postJson(route('chatbot.handle'), [
            'message' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    public function test_penanganan_error_fallback_jika_api_gemini_mengalami_gangguan(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response(['error' => 'API quota exceeded'], 500),
        ]);

        $response = $this->postJson(route('chatbot.handle'), [
            'message' => 'Halo bot',
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Maaf Kak, asisten Becks saat ini sedang mengalami gangguan teknis. Silakan coba beberapa saat lagi.',
        ]);
    }
}
