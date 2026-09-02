<section>
    <header>
        <h2 class="text-lg font-black uppercase tracking-widest text-gray-900 dark:text-gray-100">
            Perbarui Password
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 font-medium">
            Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Password Saat Ini" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full bg-slate-50 border border-slate-100 text-slate-900 rounded-xl px-4 py-3" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Password Baru" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full bg-slate-50 border border-slate-100 text-slate-900 rounded-xl px-4 py-3" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full bg-slate-50 border border-slate-100 text-slate-900 rounded-xl px-4 py-3" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
            <button class="px-6 py-2.5 rounded-xl bg-brand-900 text-white hover:bg-brand-800 shadow-lg shadow-brand-900/20 transition-all text-xs font-black uppercase tracking-widest">Simpan Password</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600 flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
