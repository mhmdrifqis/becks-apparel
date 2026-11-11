<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\Material;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_pengguna_bisa_memproses_checkout_dengan_data_valid(): void
    {
        $user = User::factory()->create();
        $package = Package::factory()->create(['base_price' => 100000]);
        $material = Material::factory()->create(['additional_price' => 10000]);

        $cartItem = CartItem::factory()->create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'material_id' => $material->id,
            'quantity' => 1,
        ]);

        $payload = [
            'recipient_name' => 'Budi Santoso',
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No 1',
            'shipping_cost' => 20000,
            'shipping_service' => 'REG',
            'courier_name' => 'JNE',
            'notes' => 'Catatan produksi sablon rapi',
            'roster' => [
                $cartItem->id => [
                    ['name' => 'Budi', 'number' => '10', 'size' => 'XXL', 'upgrades' => []],
                ]
            ],
            'designs' => []
        ];

        $response = $this->actingAs($user)->post(route('checkout.process'), $payload);

        $response->assertStatus(302); // Redirect to order invoice
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'recipient_name' => 'Budi Santoso',
            'shipping_cost' => 20000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }

    public function test_checkout_berhasil_membersihkan_item_dari_keranjang(): void
    {
        $user = User::factory()->create();
        $package = Package::factory()->create();
        $cartItem = CartItem::factory()->create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'quantity' => 1,
        ]);

        $payload = [
            'recipient_name' => 'Budi Santoso',
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No 1',
            'shipping_cost' => 15000,
            'shipping_service' => 'REG',
            'courier_name' => 'JNE',
            'roster' => [
                $cartItem->id => [
                    ['name' => 'Budi', 'number' => '10', 'size' => 'L', 'upgrades' => []],
                ]
            ]
        ];

        $this->actingAs($user)->post(route('checkout.process'), $payload);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_checkout_gagal_jika_data_penerima_kosong(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'recipient_name' => '',
            'recipient_phone' => '',
            'shipping_address' => '',
        ]);

        $response->assertSessionHasErrors(['recipient_name', 'recipient_phone', 'shipping_address']);
    }

    public function test_pengguna_bisa_melihat_invoice_pesanannya_sendiri(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('customer.orders.show', $order->order_number));

        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    }

    public function test_pengguna_tidak_bisa_melihat_pesanan_milik_orang_lain_idor(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $orderB = Order::factory()->create([
            'user_id' => $userB->id,
        ]);

        $response = $this->actingAs($userA)->get(route('customer.orders.show', $orderB->order_number));

        $response->assertStatus(403);
    }

    public function test_pengguna_bisa_membatalkan_pesanan_unpaid(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->actingAs($user)->post(route('customer.orders.cancel', $order->id));

        $response->assertRedirect(route('customer.orders'));
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_pengguna_tidak_bisa_membatalkan_pesanan_yang_sudah_dibayar(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'paid',
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($user)->post(route('customer.orders.cancel', $order->id));

        $response->assertSessionHas('error');
        $this->assertEquals('paid', $order->fresh()->status);
    }

    public function test_pengguna_bisa_konfirmasi_terima_pesanan_jika_shipped_dan_paid(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'shipped',
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($user)->post(route('customer.orders.confirm-received', $order->id));

        $response->assertRedirect(route('customer.orders.show', $order->order_number));
        $this->assertEquals('completed', $order->fresh()->status);
    }

    public function test_konfirmasi_terima_pesanan_gagal_jika_belum_dikirim_atau_belum_lunas(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'printing', // Belum shipped
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($user)->post(route('customer.orders.confirm-received', $order->id));

        $response->assertSessionHas('error');
        $this->assertEquals('printing', $order->fresh()->status);
    }
}
