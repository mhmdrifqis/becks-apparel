<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Tim Produksi']);
        Role::firstOrCreate(['name' => 'Management/Owner']);
    }

    public function test_admin_bisa_mengakses_panel_admin(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_admin_bisa_mengakses_halaman_manajemen_pesanan(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->get('/admin/orders');

        $response->assertStatus(200);
    }

    public function test_tim_produksi_bisa_mengakses_panel_produksi(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('Tim Produksi');

        $response = $this->actingAs($staff)->get('/produksi');

        $response->assertStatus(200);
    }

    public function test_owner_bisa_mengakses_panel_owner(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('Management/Owner');

        $response = $this->actingAs($owner)->get('/owner');

        $response->assertStatus(200);
    }

    public function test_pelanggan_biasa_dilarang_mengakses_panel_admin(): void
    {
        $customer = User::factory()->create(); // Tanpa role admin

        $response = $this->actingAs($customer)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_pelanggan_biasa_dilarang_mengakses_panel_produksi(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get('/produksi');

        $response->assertStatus(403);
    }

    public function test_pelanggan_biasa_dilarang_mengakses_panel_owner(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get('/owner');

        $response->assertStatus(403);
    }
}
