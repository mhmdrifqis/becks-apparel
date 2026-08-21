<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex p-3 rounded-2xl bg-brand-900/50 border border-brand-500/30 text-brand-400 mb-3 shadow-lg shadow-brand-950/50">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <h2 class="font-display text-2xl font-black tracking-tight text-white">Verifikasi OTP Lupa Password</h2>
        <p class="mt-1.5 text-xs text-zinc-400 leading-relaxed max-w-xs mx-auto">
            Masukkan 6-digit kode OTP yang telah dikirimkan ke pesan WhatsApp Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-4">
        @csrf

        <!-- OTP Input -->
        <div>
            <x-input-label for="otp" :value="__('Kode OTP 6 Digit')" class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1 text-center" />
            <x-text-input id="otp" class="block w-full px-4 py-3.5 text-center font-mono text-3xl tracking-[0.4em] font-extrabold rounded-2xl bg-zinc-800/90 border-brand-500/40 text-brand-400 focus:border-brand-400 focus:ring-brand-500/30 shadow-inner" type="text" name="otp" required autofocus maxlength="6" placeholder="000000" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2 text-center" />
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-900 to-brand-800 hover:from-brand-800 hover:to-brand-700 text-white font-bold text-sm rounded-2xl shadow-xl shadow-brand-950/40 border border-brand-700/50 transition-all transform active:scale-[0.98] uppercase tracking-wider mt-2">
            {{ __('Verifikasi OTP') }}
        </button>

        <div class="mt-6 pt-4 border-t border-white/10 text-center">
            <a class="text-xs font-bold text-brand-400 hover:text-brand-300 transition-colors" href="{{ route('password.otp.request') }}">
                Minta Kode OTP Baru
            </a>
        </div>
    </form>
</x-guest-layout>
