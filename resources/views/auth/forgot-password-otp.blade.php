<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex p-3 rounded-2xl bg-brand-900/50 border border-brand-500/30 text-brand-400 mb-3 shadow-lg shadow-brand-950/50">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        </div>
        <h2 class="font-display text-2xl font-black tracking-tight text-white">Lupa Password via WA</h2>
        <p class="mt-1.5 text-xs text-zinc-400 leading-relaxed max-w-xs mx-auto">
            Masukkan Nomor WhatsApp atau Email akun Anda. Kami akan mengirimkan 6-digit kode OTP ke WhatsApp.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.otp.send') }}" class="space-y-4">
        @csrf

        <!-- Login / Phone Input -->
        <div>
            <x-input-label for="login" :value="__('Nomor WhatsApp / Email')" class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1" />
            <x-text-input id="login" class="block w-full px-4 py-3 rounded-2xl bg-zinc-800/80 border-white/10 text-white placeholder-zinc-500 focus:border-brand-500 focus:ring-brand-500/20 transition-all text-sm" type="text" name="login" :value="old('login')" required autofocus placeholder="Contoh: 081234567890" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-900 to-brand-800 hover:from-brand-800 hover:to-brand-700 text-white font-bold text-sm rounded-2xl shadow-xl shadow-brand-950/40 border border-brand-700/50 transition-all transform active:scale-[0.98] uppercase tracking-wider mt-2">
            {{ __('Kirim Kode OTP WhatsApp') }}
        </button>

        <div class="mt-6 pt-4 border-t border-white/10 text-center">
            <a class="text-xs font-bold text-zinc-400 hover:text-white transition-colors" href="{{ route('login') }}">
                &larr; Kembali ke Halaman Login
            </a>
        </div>
    </form>
</x-guest-layout>
