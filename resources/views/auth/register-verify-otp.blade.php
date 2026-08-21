<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Verifikasi Nomor WhatsApp</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Kami telah mengirimkan 6-digit kode OTP ke nomor WhatsApp 
            <span class="font-bold text-gray-900 dark:text-white">{{ $phone }}</span>. 
            Masukkan kode tersebut untuk menyelesaikan registrasi.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register.otp.verify') }}" class="space-y-4">
        @csrf

        <!-- OTP Input -->
        <div>
            <x-input-label for="otp" :value="__('Kode OTP 6 Digit')" />
            <x-text-input id="otp" class="block mt-1 w-full text-center font-mono text-2xl tracking-[0.5em]" type="text" name="otp" required autofocus maxlength="6" placeholder="000000" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="mt-6 flex flex-col space-y-3">
            <x-primary-button class="w-full justify-center py-3 bg-brand-900 hover:bg-brand-800 text-lg font-bold">
                {{ __('Verifikasi & Buat Akun') }}
            </x-primary-button>
        </div>
    </form>

    <form method="POST" action="{{ route('register.otp.resend') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm font-bold text-brand-600 dark:text-brand-400 hover:underline">
            Belum menerima kode? Kirim Ulang OTP
        </button>
    </form>
</x-guest-layout>
