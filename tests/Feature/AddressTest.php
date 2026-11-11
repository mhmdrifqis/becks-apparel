<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_bisa_menambah_alamat_baru(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('user.addresses.store'), [
            'label' => 'Kantor',
            'nama_penerima' => 'Ahmad Alfian',
            'no_telepon' => '081234567890',
            'alamat_lengkap' => 'Jl. Jenderal Sudirman No. 45',
            'kota' => 'Tangerang',
            'provinsi' => 'Banten',
            'kode_pos' => '15111',
            'rajaongkir_city_id' => '456',
            'rajaongkir_province_id' => '3',
            'is_default' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'label' => 'Kantor',
            'nama_penerima' => 'Ahmad Alfian',
        ]);
    }

    public function test_alamat_pertama_otomatis_menjadi_default(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('user.addresses.store'), [
            'nama_penerima' => 'Ahmad Alfian',
            'no_telepon' => '081234567890',
            'alamat_lengkap' => 'Jl. Melati No. 1',
            'kota' => 'Tangerang',
            'provinsi' => 'Banten',
            'kode_pos' => '15111',
            'is_default' => false, // Sengaja false
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'is_default' => true, // Otomatis true karena alamat pertama
        ]);
    }

    public function test_tambah_alamat_gagal_jika_field_wajib_kosong(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('user.addresses.store'), [
            'nama_penerima' => '',
            'no_telepon' => '',
            'alamat_lengkap' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nama_penerima', 'no_telepon', 'alamat_lengkap']);
    }

    public function test_pengguna_bisa_mengubah_alamat_default(): void
    {
        $user = User::factory()->create();

        $address1 = UserAddress::factory()->create(['user_id' => $user->id, 'is_default' => true]);
        $address2 = UserAddress::factory()->create(['user_id' => $user->id, 'is_default' => false]);

        $response = $this->actingAs($user)->postJson(route('user.addresses.set-default', $address2->id));

        $response->assertStatus(200);
        $this->assertFalse((bool) $address1->fresh()->is_default);
        $this->assertTrue((bool) $address2->fresh()->is_default);
    }

    public function test_pengguna_tidak_bisa_menghapus_alamat_milik_orang_lain(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $addressB = UserAddress::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->deleteJson(route('user.addresses.destroy', $addressB->id));

        $response->assertStatus(403);
    }

    public function test_menghapus_alamat_default_otomatis_mengangkat_alamat_lain_menjadi_default(): void
    {
        $user = User::factory()->create();

        $addressDefault = UserAddress::factory()->create(['user_id' => $user->id, 'is_default' => true]);
        $addressOther = UserAddress::factory()->create(['user_id' => $user->id, 'is_default' => false]);

        $response = $this->actingAs($user)->deleteJson(route('user.addresses.destroy', $addressDefault->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_addresses', ['id' => $addressDefault->id]);
        $this->assertTrue((bool) $addressOther->fresh()->is_default);
    }
}
