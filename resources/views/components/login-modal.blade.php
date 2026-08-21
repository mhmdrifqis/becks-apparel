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

    <div class="flex min-h-screen items-center justify-center p-3 sm:p-4">
        <div 
            x-show="showAuthModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-md max-h-[90vh] overflow-y-auto rounded-[2rem] bg-zinc-900/95 border border-white/10 shadow-2xl text-zinc-100 custom-scrollbar"
        >
            <div class="px-6 py-6 sm:px-7 sm:py-7">
                <!-- Close Button -->
                <button @click="showAuthModal = false" class="absolute top-5 right-5 text-zinc-400 hover:text-white transition-colors p-1 rounded-full hover:bg-zinc-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Logo & Title -->
                <div class="text-center mb-5">
                    <div class="flex justify-center mb-2">
                        <x-application-logo class="h-9 w-auto fill-current text-brand-400 drop-shadow-[0_0_10px_rgba(212,175,55,0.3)]" />
                    </div>
                    <h2 class="font-display text-xl font-black tracking-tight text-white" x-text="authMode === 'login' ? 'Selamat Datang Kembali' : 'Bergabung Sekarang'"></h2>
                    <p class="text-zinc-400 text-xs mt-1" x-text="authMode === 'login' ? 'Masuk ke akun Becks Apparel Anda' : 'Buat akun untuk mulai kustomisasi jersey'"></p>
                </div>

                <!-- Login Form -->
                <div x-show="authMode === 'login'">
                    <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Nomor WhatsApp / Email</label>
                            <input type="text" name="login" value="{{ old('login') }}" required autofocus class="w-full px-4 py-2.5 rounded-xl bg-zinc-800/90 border-white/10 focus:border-brand-500 focus:ring-brand-500/20 text-xs text-white placeholder-zinc-500 transition-all" placeholder="08xxxxxxxxxx atau email">
                            @error('login') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Password</label>
                                <a href="{{ route('password.otp.request') }}" class="text-[11px] font-bold text-brand-400 hover:text-brand-300 transition-colors">Lupa Password via WA?</a>
                            </div>
                            <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-xl bg-zinc-800/90 border-white/10 focus:border-brand-500 focus:ring-brand-500/20 text-xs text-white placeholder-zinc-500 transition-all" placeholder="••••••••">
                        </div>

                        <div class="flex items-center pt-0.5">
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember" class="rounded bg-zinc-800 border-white/10 text-brand-500 focus:ring-brand-500 focus:ring-offset-zinc-900">
                                <span class="ml-2 text-xs text-zinc-400 group-hover:text-white transition-colors">Ingat saya</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-brand-900 to-brand-800 hover:from-brand-800 hover:to-brand-700 text-white rounded-xl font-bold text-xs shadow-lg shadow-brand-950/40 border border-brand-700/50 transition-all active:scale-[0.98] uppercase tracking-wider">
                            Masuk Ke Akun
                        </button>
                    </form>

                    <div class="mt-4">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-white/10"></div>
                            </div>
                            <div class="relative flex justify-center text-[11px]">
                                <span class="px-2 bg-zinc-900 text-zinc-500">Atau masuk dengan</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-2.5 border border-white/10 rounded-xl shadow-sm text-xs font-bold text-zinc-200 bg-zinc-800/60 hover:bg-zinc-800 transition-all active:scale-[0.98]">
                                <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                                        <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                                        <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -19.444 63.239 -14.754 63.239 Z"/>
                                        <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                                        <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                                    </g>
                                </svg>
                                Lanjutkan dengan Google
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-white/10 text-center">
                        <p class="text-xs text-zinc-400">
                            Belum punya akun? 
                            <button @click="authMode = 'register'" class="font-bold text-brand-400 hover:text-brand-300 transition-colors">Daftar Sekarang</button>
                        </p>
                    </div>
                </div>

                <!-- Register Form -->
                <div x-show="authMode === 'register'">
                    <form method="POST" action="{{ route('register') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-xl bg-zinc-800/90 border-white/10 focus:border-brand-500 focus:ring-brand-500/20 text-xs text-white placeholder-zinc-500 transition-all" placeholder="Nama Lengkap Anda">
                            @error('name') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Nomor WhatsApp / Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-2.5 rounded-xl bg-zinc-800/90 border-white/10 focus:border-brand-500 focus:ring-brand-500/20 text-xs text-white placeholder-zinc-500 transition-all" placeholder="Contoh: 081234567890">
                            @error('phone') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Email Address (Opsional)</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-5 py-2.5 rounded-xl bg-zinc-800/90 border-white/10 focus:border-brand-500 focus:ring-brand-500/20 text-xs text-white placeholder-zinc-500 transition-all" placeholder="nama@email.com (opsional)">
                            @error('email') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-xl bg-zinc-800/90 border-white/10 focus:border-brand-500 focus:ring-brand-500/20 text-xs text-white placeholder-zinc-500 transition-all" placeholder="Minimal 8 karakter">
                            @error('password') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 rounded-xl bg-zinc-800/90 border-white/10 focus:border-brand-500 focus:ring-brand-500/20 text-xs text-white placeholder-zinc-500 transition-all" placeholder="Ulangi password">
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-brand-900 to-brand-800 hover:from-brand-800 hover:to-brand-700 text-white rounded-xl font-bold text-xs shadow-lg shadow-brand-950/40 border border-brand-700/50 transition-all active:scale-[0.98] uppercase tracking-wider mt-1">
                            Daftar Akun (OTP WA)
                        </button>
                    </form>

                    <div class="mt-4">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-white/10"></div>
                            </div>
                            <div class="relative flex justify-center text-[11px]">
                                <span class="px-2 bg-zinc-900 text-zinc-500">Atau daftar dengan</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-2.5 border border-white/10 rounded-xl shadow-sm text-xs font-bold text-zinc-200 bg-zinc-800/60 hover:bg-zinc-800 transition-all active:scale-[0.98]">
                                <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                                        <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                                        <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
                                        <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                                        <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                                    </g>
                                </svg>
                                Lanjutkan dengan Google
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-white/10 text-center">
                        <p class="text-xs text-zinc-400">
                            Sudah punya akun? 
                            <button @click="authMode = 'login'" class="font-bold text-brand-400 hover:text-brand-300 transition-colors">Masuk Di Sini</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
