<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="font-display text-2xl font-black tracking-tight text-white">Selamat Datang Kembali</h2>
        <p class="mt-1 text-xs text-zinc-400">Masuk ke akun Becks Apparel Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email / Phone Number -->
        <div>
            <x-input-label for="login" :value="__('Nomor WhatsApp / Email')" class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1" />
            <x-text-input id="login" class="block w-full px-4 py-3 rounded-2xl bg-zinc-800/80 border-white/10 text-white placeholder-zinc-500 focus:border-brand-500 focus:ring-brand-500/20 transition-all text-sm" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="08xxxxxxxxxx atau email" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-zinc-400" />
                <a class="text-xs font-bold text-brand-400 hover:text-brand-300 transition-colors" href="{{ route('password.otp.request') }}">
                    {{ __('Lupa Password via WA?') }}
                </a>
            </div>

            <x-text-input id="password" class="block w-full px-4 py-3 rounded-2xl bg-zinc-800/80 border-white/10 text-white placeholder-zinc-500 focus:border-brand-500 focus:ring-brand-500/20 transition-all text-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded bg-zinc-800 border-white/10 text-brand-500 focus:ring-brand-500 focus:ring-offset-zinc-900" name="remember">
                <span class="ms-2 text-xs text-zinc-400 group-hover:text-white transition-colors">{{ __('Remember me') }}</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-900 to-brand-800 hover:from-brand-800 hover:to-brand-700 text-white font-bold text-sm rounded-2xl shadow-xl shadow-brand-950/40 border border-brand-700/50 transition-all transform active:scale-[0.98] uppercase tracking-wider">
            {{ __('Masuk Ke Akun') }}
        </button>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-3 bg-zinc-900 text-zinc-500">Atau masuk dengan</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-3 border border-white/10 rounded-2xl shadow-sm text-xs font-bold text-zinc-200 bg-zinc-800/60 hover:bg-zinc-800 hover:border-white/20 transition-all active:scale-[0.98]">
                    <svg class="h-4 w-4 mr-2.5" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
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

        <div class="mt-6 pt-4 border-t border-white/10 text-center">
            <p class="text-xs text-zinc-400">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-brand-400 hover:text-brand-300 transition-colors">Daftar Sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>
