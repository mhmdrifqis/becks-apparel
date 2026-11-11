<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShippingTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_bisa_mengambil_daftar_provinsi_dengan_mock(): void
    {
        $user = User::factory()->create();

        Http::fake([
            '*/destination/province' => Http::response([
                'meta' => ['code' => 200],
                'data' => [
                    ['id' => '1', 'name' => 'Bali'],
                    ['id' => '3', 'name' => 'Banten'],
                ]
            ], 200),
        ]);

        $response = $this->actingAs($user)->getJson(route('shipping.provinces'));

        $response->assertStatus(200);
        $response->assertJsonFragment(['province' => 'Banten']);
    }

    public function test_pengguna_bisa_mengambil_daftar_kota_dengan_mock(): void
    {
        $user = User::factory()->create();

        Http::fake([
            '*/destination/city/*' => Http::response([
                'meta' => ['code' => 200],
                'data' => [
                    ['id' => '456', 'name' => 'Tangerang'],
                    ['id' => '457', 'name' => 'Tangerang Selatan'],
                ]
            ], 200),
        ]);

        $response = $this->actingAs($user)->getJson(route('shipping.cities', 3));

        $response->assertStatus(200);
        $response->assertJsonFragment(['city_name' => 'Tangerang']);
    }

    public function test_pengguna_bisa_menghitung_ongkos_kirim_manual_dengan_mock(): void
    {
        $user = User::factory()->create();

        Http::fake([
            '*/calculate/domestic-cost' => Http::response([
                'meta' => ['code' => 200],
                'data' => [
                    [
                        'costs' => [
                            [
                                'service' => 'REG',
                                'description' => 'Layanan Reguler',
                                'cost' => [['value' => 20000, 'etd' => '2-3']]
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $response = $this->actingAs($user)->postJson(route('shipping.cost'), [
            'destination' => 456,
            'weight' => 1000,
            'courier' => 'jne',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['service' => 'REG']);
    }

    public function test_hitung_ongkir_gagal_jika_parameter_tujuan_dan_berat_kosong(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('shipping.cost'), [
            'destination' => '',
            'weight' => '',
            'courier' => 'jne',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['destination', 'weight']);
    }

    public function test_pengguna_bisa_melacak_resi_pesanan(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'courier_name' => 'JNE',
            'tracking_number' => 'SOC123456789',
        ]);

        Http::fake([
            'https://api.biteship.com/v1/trackings/*' => Http::response([
                'courier' => ['name' => 'JNE'],
                'status' => 'IN_TRANSIT',
                'history' => [
                    ['note' => 'Paket sedang dikirim ke Tangerang', 'updated_at' => now()->toIso8601String()]
                ]
            ], 200),
        ]);

        $response = $this->actingAs($user)->getJson(route('shipping.track', $order->id));

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'IN_TRANSIT']);
    }

    public function test_lacak_resi_gagal_jika_nomor_resi_belum_diinput_oleh_admin(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'tracking_number' => null,
        ]);

        $response = $this->actingAs($user)->getJson(route('shipping.track', $order->id));

        $response->assertStatus(400);
        $response->assertJsonFragment(['success' => false]);
    }
}
