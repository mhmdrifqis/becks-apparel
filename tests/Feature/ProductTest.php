<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_bisa_melihat_katalog_produk_aktif(): void
    {
        $package = Package::factory()->create([
            'name' => 'Jersey Paket C Full Printing',
            'category' => 'jersey',
            'is_active' => true,
        ]);

        $response = $this->get('/catalog');

        $response->assertStatus(200);
        $response->assertSee('Jersey Paket C Full Printing');
    }

    public function test_produk_non_aktif_tidak_muncul_di_katalog(): void
    {
        $inactivePackage = Package::factory()->create([
            'name' => 'Jersey Paket Rahasia Non-Aktif',
            'is_active' => false,
        ]);

        $response = $this->get('/catalog');

        $response->assertStatus(200);
        $response->assertDontSee('Jersey Paket Rahasia Non-Aktif');
    }

    public function test_pengguna_bisa_melihat_detail_produk_dan_bahan_terkait(): void
    {
        $package = Package::factory()->create([
            'name' => 'Jersey Paket A',
            'slug' => 'jersey-paket-a',
            'category' => 'jersey',
            'is_active' => true,
        ]);

        $material = Material::factory()->create([
            'name' => 'Dryfit Milano Standar',
            'product_types' => ['jersey'],
        ]);

        $response = $this->get('/catalog/jersey-paket-a');

        $response->assertStatus(200);
        $response->assertSee('Jersey Paket A');
        $response->assertSee('Dryfit Milano Standar');
    }

    public function test_detail_produk_mengembalikan_404_jika_slug_tidak_ditemukan(): void
    {
        $response = $this->get('/catalog/slug-produk-palsu-123');

        $response->assertStatus(404);
    }
}
