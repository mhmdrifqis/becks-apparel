<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OtpPasswordResetController extends Controller
{
    protected WhatsAppService $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Tampilkan form permintaan OTP (Lupa Password via WhatsApp)
     */
    public function showRequestForm(): View
    {
        return view('auth.forgot-password-otp');
    }

    /**
     * Kirim OTP 6 digit ke nomor WhatsApp pengguna
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
        ], [
            'login.required' => 'Masukkan Nomor WhatsApp atau Email Anda.',
        ]);

        $login = trim($request->login);
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            $user = User::where('email', $login)->first();
        } else {
            $phone = PhoneHelper::normalize($login);
            $user = User::where('phone', $phone)->first();
        }

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => 'Akun dengan Nomor WhatsApp/Email tersebut tidak ditemukan.',
            ]);
        }

        if (empty($user->phone)) {
            throw ValidationException::withMessages([
                'login' => 'Akun Anda tidak memiliki nomor WhatsApp terdaftar. Hubungi Customer Service.',
            ]);
        }

        // Generate 6 digit OTP
        $otp = (string) rand(100000, 999999);

        // Cache OTP selama 5 menit (300 detik)
        Cache::put("otp_reset_{$user->id}", [
            'code' => $otp,
            'phone' => $user->phone,
        ], 300);

        // Simpan user_id di session untuk tahap verifikasi
        session(['otp_user_id' => $user->id]);

        // Kirim via WhatsApp Service
        $message = "🔐 *KODE OTP BECKS APPAREL*\n\nKode OTP untuk reset password Anda adalah: *{$otp}*\n\nKode ini berlaku selama *5 menit*. Demi keamanan, jangan berikan kode ini kepada siapapun.";
        
        $res = $this->whatsapp->sendMessage($user->phone, $message);
        Log::info("Sent OTP to {$user->phone}: {$otp}. Result: " . json_encode($res));

        $statusMsg = 'Kode OTP 6-digit telah dikirimkan ke WhatsApp Anda (' . substr($user->phone, 0, 4) . '****' . substr($user->phone, -3) . ').';
        if (isset($res['status']) && $res['status'] === false) {
            $statusMsg .= ' (Catatan: Gateway Fonnte WA terputus: "' . ($res['reason'] ?? 'Device Disconnected') . '". Kode OTP Anda: ' . $otp . ')';
        }

        return redirect()->route('password.otp.verify-form')
            ->with('status', $statusMsg);
    }

    /**
     * Tampilkan form verifikasi OTP
     */
    public function showVerifyForm(): View|RedirectResponse
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('password.otp.request')
                ->withErrors(['login' => 'Silakan masukkan nomor WhatsApp Anda terlebih dahulu.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Verifikasi kode OTP yang diinputkan pengguna
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ], [
            'otp.required' => 'Masukkan 6-digit kode OTP.',
            'otp.digits' => 'Kode OTP harus berupa 6 angka.',
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('password.otp.request');
        }

        $cachedOtp = Cache::get("otp_reset_{$userId}");

        if (!$cachedOtp || $cachedOtp['code'] !== trim($request->otp)) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP salah atau telah kedaluwarsa. Silakan minta OTP baru.',
            ]);
        }

        // OTP valid, beri otorisasi ke halaman reset password
        session(['otp_verified_user_id' => $userId]);

        return redirect()->route('password.otp.reset-form');
    }

    /**
     * Tampilkan form buat password baru
     */
    public function showResetForm(): View|RedirectResponse
    {
        if (!session()->has('otp_verified_user_id')) {
            return redirect()->route('password.otp.request');
        }

        return view('auth.reset-password-otp');
    }

    /**
     * Simpan password baru pengguna
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $userId = session('otp_verified_user_id');
        if (!$userId) {
            return redirect()->route('password.otp.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::findOrFail($userId);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Bersihkan Cache & Session OTP
        Cache::forget("otp_reset_{$userId}");
        session()->forget(['otp_user_id', 'otp_verified_user_id']);

        return redirect()->route('login')
            ->with('status', 'Password Anda berhasil diperbarui! Silakan masuk dengan password baru.');
    }
}
