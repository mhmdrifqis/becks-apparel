<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex p-3 rounded-2xl bg-brand-900/50 border border-brand-500/30 text-brand-400 mb-3 shadow-lg shadow-brand-950/50">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457-.39-2.823-1.07-4"/></svg>
        </div>
        <h2 class="font-display text-2xl font-black tracking-tight text-white">Verifikasi OTP Registrasi</h2>
        <p class="mt-1.5 text-xs text-zinc-400 leading-relaxed max-w-xs mx-auto">
            Kode OTP 6-digit telah dikirimkan ke WhatsApp <span class="font-bold text-brand-300">{{ $phone }}</span>.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register.otp.verify') }}" class="space-y-4">
        @csrf

        <!-- OTP Input -->
        <div>
            <x-input-label for="otp" :value="__('Kode OTP 6 Digit')" class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1 text-center" />
            <x-text-input id="otp" class="block w-full px-4 py-3.5 text-center font-mono text-3xl tracking-[0.4em] font-extrabold rounded-2xl bg-zinc-800/90 border-brand-500/40 text-brand-400 focus:border-brand-400 focus:ring-brand-500/30 shadow-inner" type="text" name="otp" required autofocus maxlength="6" placeholder="000000" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2 text-center" />
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-900 to-brand-800 hover:from-brand-800 hover:to-brand-700 text-white font-bold text-sm rounded-2xl shadow-xl shadow-brand-950/40 border border-brand-700/50 transition-all transform active:scale-[0.98] uppercase tracking-wider mt-2">
            {{ __('Verifikasi & Buat Akun') }}
        </button>
    </form>

    <form method="POST" action="{{ route('register.otp.resend') }}" class="mt-6 pt-4 border-t border-white/10 text-center">
        @csrf
        <button type="submit" class="text-xs font-bold text-brand-400 hover:text-brand-300 transition-colors">
            Belum menerima kode? Kirim Ulang OTP
        </button>
    </form>
</x-guest-layout>
