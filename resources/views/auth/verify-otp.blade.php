<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Verifikasi Kode OTP</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Masukkan 6-digit kode OTP yang telah dikirimkan ke pesan WhatsApp Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-4">
        @csrf

        <!-- OTP Input -->
        <div>
            <x-input-label for="otp" :value="__('Kode OTP 6 Digit')" />
            <x-text-input id="otp" class="block mt-1 w-full text-center font-mono text-2xl tracking-[0.5em]" type="text" name="otp" required autofocus maxlength="6" placeholder="000000" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white" href="{{ route('password.otp.request') }}">
                Minta OTP Baru
            </a>

            <x-primary-button class="bg-brand-900 hover:bg-brand-800">
                {{ __('Verifikasi OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
