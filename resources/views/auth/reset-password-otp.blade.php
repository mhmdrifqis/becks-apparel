<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex p-3 rounded-2xl bg-brand-900/50 border border-brand-500/30 text-brand-400 mb-3 shadow-lg shadow-brand-950/50">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>
        <h2 class="font-display text-2xl font-black tracking-tight text-white">Buat Password Baru</h2>
        <p class="mt-1.5 text-xs text-zinc-400 leading-relaxed max-w-xs mx-auto">
            OTP berhasil diverifikasi. Silakan tentukan password baru untuk akun Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.otp.reset') }}" class="space-y-4">
        @csrf

        <!-- New Password -->
        <div>
            <x-input-label for="password" :value="__('Password Baru')" class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1" />
            <x-text-input id="password" class="block w-full px-4 py-3 rounded-2xl bg-zinc-800/80 border-white/10 text-white placeholder-zinc-500 focus:border-brand-500 focus:ring-brand-500/20 transition-all text-sm" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1" />
            <x-text-input id="password_confirmation" class="block w-full px-4 py-3 rounded-2xl bg-zinc-800/80 border-white/10 text-white placeholder-zinc-500 focus:border-brand-500 focus:ring-brand-500/20 transition-all text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-900 to-brand-800 hover:from-brand-800 hover:to-brand-700 text-white font-bold text-sm rounded-2xl shadow-xl shadow-brand-950/40 border border-brand-700/50 transition-all transform active:scale-[0.98] uppercase tracking-wider mt-2">
            {{ __('Simpan Password Baru') }}
        </button>
    </form>
</x-guest-layout>
