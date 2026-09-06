<section class="space-y-6">
    <header>
        <h2 class="text-lg font-black uppercase tracking-widest text-red-600 dark:text-red-400">
            Hapus Akun
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 font-medium">
            Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all text-xs font-black uppercase tracking-widest"
    >Hapus Akun</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-black text-gray-900 dark:text-gray-100 uppercase tracking-widest">
                Apakah Anda yakin ingin menghapus akun?
            </h2>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Password" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full bg-slate-50 border border-slate-100 text-slate-900 rounded-xl px-4 py-3"
                    placeholder="Masukkan Password"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-all text-xs font-bold uppercase tracking-widest">
                    Batal
                </button>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all text-xs font-black uppercase tracking-widest">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
