<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\Material;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_login_bisa_menambah_produk_ke_keranjang(): void
    {
        $user = User::factory()->create();
        $package = Package::factory()->create();
        $material = Material::factory()->create();

        $response = $this->actingAs($user)->postJson(route('cart.add', $package->slug), [
            'material_id' => $material->id,
            'quantity' => 5,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'material_id' => $material->id,
            'quantity' => 5,
        ]);
    }

    public function test_menambah_produk_yang_sama_mengakumulasi_kuantitas(): void
    {
        $user = User::factory()->create();
        $package = Package::factory()->create();
        $material = Material::factory()->create();

        CartItem::factory()->create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'material_id' => $material->id,
            'quantity' => 3,
        ]);

        $response = $this->actingAs($user)->postJson(route('cart.add', $package->slug), [
            'material_id' => $material->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'material_id' => $material->id,
            'quantity' => 5,
        ]);
    }

    public function test_pengguna_belum_login_tidak_bisa_menambah_ke_keranjang(): void
    {
        $package = Package::factory()->create();

        $response = $this->postJson(route('cart.add', $package->id), [
            'quantity' => 1,
        ]);

        $response->assertStatus(401);
    }

    public function test_pengguna_bisa_mengupdate_kuantitas_item_keranjang(): void
    {
        $user = User::factory()->create();
        $cartItem = CartItem::factory()->create([
            'user_id' => $user->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->patchJson(route('cart.update', $cartItem->id), [
            'quantity' => 10,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(10, $cartItem->fresh()->quantity);
    }

    public function test_update_kuantitas_dengan_angka_kurang_dari_satu_otomatis_di_set_ke_satu(): void
    {
        $user = User::factory()->create();
        $cartItem = CartItem::factory()->create([
            'user_id' => $user->id,
            'quantity' => 5,
        ]);

        $response = $this->actingAs($user)->patchJson(route('cart.update', $cartItem->id), [
            'quantity' => -2,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $cartItem->fresh()->quantity);
    }

    public function test_pengguna_tidak_bisa_mengubah_kuantitas_item_keranjang_milik_orang_lain(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $cartItemB = CartItem::factory()->create([
            'user_id' => $userB->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($userA)->patchJson(route('cart.update', $cartItemB->id), [
            'quantity' => 10,
        ]);

        $response->assertStatus(403);
    }

    public function test_pengguna_bisa_menghapus_item_dari_keranjang(): void
    {
        $user = User::factory()->create();
        $cartItem = CartItem::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson(route('cart.destroy', $cartItem->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_pengguna_bisa_mengambil_counter_realtime_keranjang(): void
    {
        $user = User::factory()->create();
        CartItem::factory()->create(['user_id' => $user->id, 'quantity' => 4]);

        $response = $this->actingAs($user)->getJson(route('cart.counts'));

        $response->assertStatus(200);
        $response->assertJson(['cart' => 4]);
    }
}
