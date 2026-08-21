<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Lupa Password via WhatsApp</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Masukkan Nomor WhatsApp atau Email akun Anda. Kami akan mengirimkan 6-digit kode OTP ke WhatsApp untuk menyetel ulang password.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.otp.send') }}" class="space-y-4">
        @csrf

        <!-- Login / Phone Input -->
        <div>
            <x-input-label for="login" :value="__('Nomor WhatsApp / Email')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus placeholder="Contoh: 081234567890" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="{{ route('login') }}">
                &larr; Kembali ke Login
            </a>

            <x-primary-button class="bg-brand-900 hover:bg-brand-800">
                {{ __('Kirim Kode OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
