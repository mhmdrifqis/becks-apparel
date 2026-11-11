<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Pelanggan']);
    }

    public function test_pengguna_bisa_mengakses_halaman_register(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_pengguna_bisa_registrasi_dan_menerima_otp(): void
    {
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('register.otp.show'));
        $this->assertEquals('081234567890', session('pending_reg_phone'));
        $this->assertTrue(Cache::has('reg_otp_081234567890'));
    }

    public function test_registrasi_gagal_jika_nomor_telepon_sudah_terdaftar(): void
    {
        User::factory()->create(['phone' => '081234567890']);

        $response = $this->post('/register', [
            'name' => 'Budi Lain',
            'phone' => '081234567890',
            'email' => 'budilain@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_registrasi_gagal_jika_konfirmasi_password_tidak_cocok(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'phone' => '081234567891',
            'email' => 'budi2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'passwordBeda',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_pengguna_bisa_verifikasi_otp_registrasi_dan_login(): void
    {
        $phone = '081234567890';
        Cache::put("reg_otp_{$phone}", [
            'name' => 'Budi Santoso',
            'phone' => $phone,
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'otp' => '123456',
        ], 600);

        session(['pending_reg_phone' => $phone]);

        $response = $this->post(route('register.otp.verify'), [
            'otp' => '123456',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['phone' => $phone]);
    }

    public function test_verifikasi_otp_gagal_jika_kode_salah(): void
    {
        $phone = '081234567890';
        Cache::put("reg_otp_{$phone}", [
            'name' => 'Budi Santoso',
            'phone' => $phone,
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'otp' => '123456',
        ], 600);

        session(['pending_reg_phone' => $phone]);

        $response = $this->post(route('register.otp.verify'), [
            'otp' => '999999',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertGuest();
    }

    public function test_pengguna_bisa_login_dengan_kredensial_valid(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'user@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_login_gagal_jika_password_salah(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_login_gagal_jika_input_kosong(): void
    {
        $response = $this->post('/login', [
            'login' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['login', 'password']);
    }

    public function test_pengguna_bisa_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
