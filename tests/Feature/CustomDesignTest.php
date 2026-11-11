<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Design;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomDesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_bisa_mengakses_halaman_customizer(): void
    {
        $response = $this->get('/customizer');
        $response->assertStatus(200);
    }

    public function test_pengguna_bisa_menyimpan_desain_baru(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        // 1x1 base64 transparent PNG
        $dummyBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->actingAs($user)->postJson(route('customer.designs.store'), [
            'name' => 'Desain Jersey Garuda',
            'design_json' => json_encode(['model' => 'v-neck-short', 'colors' => ['body' => '#ff0000']]),
            'preview_image' => $dummyBase64,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('designs', [
            'user_id' => $user->id,
            'name' => 'Desain Jersey Garuda',
        ]);
    }

    public function test_simpan_desain_gagal_jika_payload_tidak_lengkap(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('customer.designs.store'), [
            'name' => '',
            'design_json' => '',
            'preview_image' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'design_json', 'preview_image']);
    }

    public function test_pengguna_bisa_memperbarui_desain_miliknya(): void
    {
        $user = User::factory()->create();
        $design = Design::factory()->create([
            'user_id' => $user->id,
            'name' => 'Desain Versi 1',
        ]);

        $response = $this->actingAs($user)->patchJson(route('customer.designs.update', $design->id), [
            'name' => 'Desain Versi 2 Final',
            'design_json' => json_encode(['model' => 'v-neck-short', 'colors' => ['body' => '#00ff00']]),
        ]);

        $response->assertStatus(200);
        $this->assertEquals('Desain Versi 2 Final', $design->fresh()->name);
    }

    public function test_pengguna_tidak_bisa_mengubah_atau_menghapus_desain_orang_lain(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $designB = Design::factory()->create(['user_id' => $userB->id]);

        $responseUpdate = $this->actingAs($userA)->patchJson(route('customer.designs.update', $designB->id), [
            'name' => 'Hacked Design',
            'design_json' => '{}',
        ]);
        $responseUpdate->assertStatus(403);

        $responseDelete = $this->actingAs($userA)->delete(route('customer.designs.destroy', $designB->id));
        $responseDelete->assertSessionHas('error', 'Unauthorized');
    }

    public function test_pengguna_bisa_menghapus_desain_miliknya(): void
    {
        $user = User::factory()->create();
        $design = Design::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('customer.designs.destroy', $design->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('designs', ['id' => $design->id]);
    }
}
