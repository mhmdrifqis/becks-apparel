<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use App\Helpers\PhoneHelper;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        if ($loginType === 'phone') {
            $login = PhoneHelper::normalize($login);
        }

        // Cek ketersediaan akun terlebih dahulu
        $user = \App\Models\User::where($loginType, $login)->first();
        if (!$user) {
            $msg = ($loginType === 'email') 
                ? 'Email yang Anda masukkan belum terdaftar. Silakan mendaftar dahulu.' 
                : 'Nomor WhatsApp/Telepon (' . $login . ') belum terdaftar. Silakan mendaftar dahulu.';
            
            throw ValidationException::withMessages([
                'login' => $msg,
            ]);
        }

        if (! Auth::attempt([$loginType => $login, 'password' => $password], $remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'Password yang Anda masukkan salah. Silakan coba lagi atau gunakan Lupa Password via WhatsApp.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $login = $this->string('login');
        if (! filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $login = PhoneHelper::normalize($login);
        }

        return Str::transliterate(Str::lower($login).'|'.$this->ip());
    }
}
