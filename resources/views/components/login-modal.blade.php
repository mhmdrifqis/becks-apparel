<div 
    x-show="showAuthModal" 
    x-cloak
    class="fixed inset-0 z-[100] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true"
>
    <!-- Backdrop -->
    <div 
        x-show="showAuthModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="showAuthModal = false"
        class="fixed inset-0 bg-zinc-950/80 backdrop-blur-sm transition-opacity"
    ></div>

    <div class="flex min-h-screen items-center justify-center p-4">
        <div 
            x-show="showAuthModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-lg overflow-hidden rounded-[2.5rem] bg-white dark:bg-zinc-900 shadow-2xl border border-white/10"
        >
            <div class="px-8 pt-8 pb-10">
                <!-- Close Button -->
                <button @click="showAuthModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Logo & Title -->
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        <x-application-logo class="h-12 w-auto fill-current text-brand-900 dark:text-brand-400" />
                    </div>
                    <h2 class="text-3xl font-black tracking-tighter" x-text="authMode === 'login' ? 'Selamat Datang Kembali' : 'Bergabung Sekarng'"></h2>
                    <p class="text-gray-500 dark:text-zinc-400 text-sm mt-2" x-text="authMode === 'login' ? 'Masuk ke akun Becks Apparel Anda' : 'Buat akun untuk mulai kustomisasi jersey'"></p>
                </div>

                <!-- Login Form -->
                <div x-show="authMode === 'login'">
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-zinc-500 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-5 py-4 rounded-2xl bg-gray-50 dark:bg-zinc-800 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-zinc-950 focus:ring-0 transition-all dark:text-white" placeholder="nama@email.com">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-zinc-500">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">Lupa Password?</a>
                                @endif
                            </div>
                            <input type="password" name="password" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 dark:bg-zinc-800 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-zinc-950 focus:ring-0 transition-all dark:text-white" placeholder="••••••••">
                        </div>

                        <div className="flex items-center">
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember" class="rounded-md bg-gray-100 dark:bg-zinc-800 border-transparent text-brand-900 focus:ring-brand-500 transition-all">
                                <span class="ml-2 text-sm text-gray-600 dark:text-zinc-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Ingat saya</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-4 bg-brand-900 border border-brand-700 hover:bg-brand-800 text-white rounded-2xl font-bold text-lg shadow-xl shadow-brand-950/20 transition-all active:scale-95 uppercase tracking-tighter">
                            Masuk Ke Akun
                        </button>
                    </form>

                    <div class="mt-8 pt-8 border-t dark:border-zinc-800 text-center">
                        <p class="text-sm text-gray-600 dark:text-zinc-400">
                            Belum punya akun? 
                            <button @click="authMode = 'register'" class="font-bold text-brand-900 dark:text-brand-400 hover:underline">Daftar Sekarang</button>
                        </p>
                    </div>
                </div>

                <!-- Register Form -->
                <div x-show="authMode === 'register'">
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-zinc-500 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-zinc-800 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-zinc-950 focus:ring-0 transition-all dark:text-white" placeholder="John Doe">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-zinc-500 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-zinc-800 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-zinc-950 focus:ring-0 transition-all dark:text-white" placeholder="nama@email.com">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-zinc-500 mb-2">Password</label>
                            <input type="password" name="password" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-zinc-800 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-zinc-950 focus:ring-0 transition-all dark:text-white" placeholder="Minimal 8 karakter">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-zinc-500 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-zinc-800 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-zinc-950 focus:ring-0 transition-all dark:text-white" placeholder="Ulangi password">
                        </div>

                        <button type="submit" class="w-full py-4 bg-brand-900 border border-brand-700 hover:bg-brand-800 text-white rounded-2xl font-bold text-lg shadow-xl shadow-brand-950/20 transition-all active:scale-95 uppercase tracking-tighter">
                            Buat Akun Baru
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t dark:border-zinc-800 text-center">
                        <p class="text-sm text-gray-600 dark:text-zinc-400">
                            Sudah punya akun? 
                            <button @click="authMode = 'login'" class="font-bold text-brand-900 dark:text-brand-400 hover:underline">Masuk Di Sini</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
