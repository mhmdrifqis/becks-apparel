<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected WhatsAppService $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request and send WhatsApp OTP.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+?62|0)8[1-9][0-9]{7,11}$/', 'unique:'.User::class],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Nama Lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp/Telepon wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp/Telepon harus nomor Indonesia yang valid (contoh: 081234567890).',
            'phone.unique' => 'Nomor WhatsApp/Telepon ini sudah terdaftar. Silakan login atau gunakan nomor lain.',
            'email.email' => 'Format email yang Anda masukkan tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang Anda ketik.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        $phone = PhoneHelper::normalize($request->phone);
        $email = $request->filled('email') ? $request->email : $phone . '@becksapparel.com';

        // Generate 6 digit OTP
        $otp = (string) rand(100000, 999999);

        // Cache draft data pendaftaran selama 10 menit
        Cache::put("reg_otp_{$phone}", [
            'name' => $request->name,
            'phone' => $phone,
            'email' => $email,
            'password' => Hash::make($request->password),
            'otp' => $otp,
        ], 600);

        session(['pending_reg_phone' => $phone]);

        // Kirim OTP via WhatsApp Service
        $message = "🎉 *PENDAFTARAN AKUN BECKS APPAREL*\n\nKode OTP verifikasi pendaftaran Anda adalah: *{$otp}*\n\nKode ini berlaku selama *10 menit*. Jangan berikan kode ini kepada siapapun.";
        
        $res = $this->whatsapp->sendMessage($phone, $message);
        Log::info("Sent Registration OTP to {$phone}: {$otp}. Result: " . json_encode($res));

        $statusMsg = 'Kode OTP verifikasi 6-digit telah dikirimkan ke WhatsApp Anda (' . substr($phone, 0, 4) . '****' . substr($phone, -3) . ').';
        if (isset($res['status']) && $res['status'] === false) {
            $statusMsg .= ' (Catatan: Gateway Fonnte WA terputus: "' . ($res['reason'] ?? 'Device Disconnected') . '". Kode OTP Anda: ' . $otp . ')';
        }

        return redirect()->route('register.otp.show')->with('status', $statusMsg);
    }

    /**
     * Tampilkan form verifikasi OTP pendaftaran
     */
    public function showVerifyOtpForm(): View|RedirectResponse
    {
        if (!session()->has('pending_reg_phone')) {
            return redirect()->route('register');
        }

        $phone = session('pending_reg_phone');
        $regData = Cache::get("reg_otp_{$phone}");

        if (!$regData) {
            return redirect()->route('register')->withErrors(['phone' => 'Sesi pendaftaran telah kedaluwarsa. Silakan isi form kembali.']);
        }

        return view('auth.register-verify-otp', [
            'phone' => $phone,
        ]);
    }

    /**
     * Verifikasi OTP dan buat akun pengguna baru
     */
    public function verifyRegistrationOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ], [
            'otp.required' => 'Masukkan 6-digit kode OTP.',
            'otp.digits' => 'Kode OTP harus berupa 6 angka.',
        ]);

        $phone = session('pending_reg_phone');
        if (!$phone) {
            return redirect()->route('register');
        }

        $regData = Cache::get("reg_otp_{$phone}");

        if (!$regData || $regData['otp'] !== trim($request->otp)) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP verifikasi pendaftaran salah atau telah kedaluwarsa.',
            ]);
        }

        // Double-check jika nomor HP/Email sudah terdaftar saat menunggu OTP
        if (User::where('phone', $regData['phone'])->exists()) {
            Cache::forget("reg_otp_{$phone}");
            session()->forget('pending_reg_phone');
            return redirect()->route('register')->withErrors(['phone' => 'Nomor WhatsApp/Telepon ini sudah terdaftar. Silakan masuk.']);
        }

        if (User::where('email', $regData['email'])->exists()) {
            Cache::forget("reg_otp_{$phone}");
            session()->forget('pending_reg_phone');
            return redirect()->route('register')->withErrors(['email' => 'Email ini sudah terdaftar. Silakan gunakan email lain.']);
        }

        // Buat User Baru di Database
        $user = User::create([
            'name' => $regData['name'],
            'phone' => $regData['phone'],
            'email' => $regData['email'],
            'password' => $regData['password'],
        ]);

        // Beri Role Pelanggan
        $user->assignRole('Pelanggan');

        // Hapus Cache & Session Pendaftaran
        Cache::forget("reg_otp_{$phone}");
        session()->forget('pending_reg_phone');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))
            ->with('success', 'Pendaftaran berhasil! Selamat datang di Becks Apparel.');
    }

    /**
     * Kirim ulang kode OTP pendaftaran
     */
    public function resendRegistrationOtp(): RedirectResponse
    {
        $phone = session('pending_reg_phone');
        if (!$phone) {
            return redirect()->route('register');
        }

        $regData = Cache::get("reg_otp_{$phone}");
        if (!$regData) {
            return redirect()->route('register')->withErrors(['phone' => 'Sesi pendaftaran kedaluwarsa. Silakan mendaftar ulang.']);
        }

        $newOtp = (string) rand(100000, 999999);
        $regData['otp'] = $newOtp;
        Cache::put("reg_otp_{$phone}", $regData, 600);

        $message = "🎉 *PENDAFTARAN AKUN BECKS APPAREL (KIRIM ULANG)*\n\nKode OTP verifikasi pendaftaran baru Anda adalah: *{$newOtp}*\n\nKode ini berlaku selama *10 menit*. Jangan berikan kode ini kepada siapapun.";
        
        $res = $this->whatsapp->sendMessage($phone, $message);
        Log::info("Resent Registration OTP to {$phone}: {$newOtp}. Result: " . json_encode($res));

        $statusMsg = 'Kode OTP baru telah dikirimkan ke WhatsApp Anda (' . substr($phone, 0, 4) . '****' . substr($phone, -3) . ').';
        if (isset($res['status']) && $res['status'] === false) {
            $statusMsg .= ' (Catatan: Gateway Fonnte WA terputus: "' . ($res['reason'] ?? 'Device Disconnected') . '". Kode OTP Anda: ' . $newOtp . ')';
        }

        return redirect()->route('register.otp.show')->with('status', $statusMsg);
    }
}
