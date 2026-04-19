<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20) ? true : false"
     :class="{ 'bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md shadow-lg py-3': scrolled, 'bg-transparent py-5': !scrolled }"
     class="fixed w-full z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div className="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group text-decoration-none">
                    <x-application-logo class="h-10 w-auto fill-current text-brand-900 dark:text-brand-400 group-hover:scale-110 transition-transform duration-300" />
                    <span class="font-bold text-xl tracking-tighter" :class="scrolled ? 'text-gray-900 dark:text-white' : 'text-gray-900 dark:text-white lg:text-white'">
                        BECKS<span class="text-brand-600 dark:text-brand-400">APPAREL</span>
                    </span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden sm:flex sm:items-center sm:gap-8">
                <a href="{{ url('/') }}" :class="scrolled ? 'text-gray-900 dark:text-white' : 'text-gray-900 dark:text-white lg:text-white'" class="text-sm font-medium hover:text-brand-600 transition-colors">Home</a>
                <a href="{{ route('catalog.index') }}" :class="scrolled ? 'text-gray-900 dark:text-white' : 'text-gray-900 dark:text-white lg:text-white'" class="text-sm font-medium hover:text-brand-600 transition-colors">Katalog</a>
                <a href="{{ route('customizer') }}" :class="scrolled ? 'text-gray-900 dark:text-white' : 'text-gray-900 dark:text-white lg:text-white'" class="text-sm font-medium hover:text-brand-600 transition-colors">Customizer</a>
                <div class="h-6 w-px" :class="scrolled ? 'bg-gray-200 dark:bg-zinc-800' : 'bg-gray-200 dark:bg-zinc-800 lg:bg-white/20'"></div>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-brand-700 dark:text-brand-400 hover:text-brand-800 transition-colors">Dashboard</a>
                @else
                    <div class="flex gap-4 items-center">
                        <button @click="showAuthModal = true; authMode = 'login'" class="bg-brand-900 hover:bg-brand-800 text-white px-5 py-2 rounded-full text-sm font-bold transition-all shadow-md active:scale-95">Login</button>
                    </div>
                @endauth

                <button onclick="toggleTheme()" class="p-2 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200 transition-colors duration-300 focus:outline-none">
                    <!-- Sun icon shown in dark mode -->
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2.25m0 13.5V21m8.966-8.966h-2.25M3.75 12h2.25m13.364-7.364l-1.591 1.591M6.756 17.244l-1.591 1.591m12.728 0l-1.591-1.591M6.756 6.756L5.165 5.165M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                    <!-- Moon icon shown in light mode -->
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                </button>
            </div>

            <!-- Mobile Button -->
            <div class="flex sm:hidden items-center gap-4">
                <button onclick="toggleTheme()" class="p-2 text-gray-600 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>
                <button @click="open = !open" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="sm:hidden bg-white dark:bg-zinc-900 border-b dark:border-zinc-800">
        <div class="px-4 pt-2 pb-6 space-y-1">
            <a href="{{ url('/') }}" class="block px-3 py-2 text-base font-medium rounded-md hover:bg-gray-100 dark:hover:bg-zinc-800">Home</a>
            <a href="{{ route('catalog.index') }}" class="block px-3 py-2 text-base font-medium rounded-md hover:bg-gray-100 dark:hover:bg-zinc-800">Katalog</a>
            <a href="{{ route('customizer') }}" class="block px-3 py-2 text-base font-medium rounded-md hover:bg-gray-100 dark:hover:bg-zinc-800">Customizer</a>
            <div class="pt-4 flex flex-col gap-2">
                 @auth
                    <a href="{{ route('dashboard') }}" class="w-full text-center bg-brand-900 text-white py-2 rounded-md font-bold">Dashboard</a>
                 @else
                    <button @click="showAuthModal = true; authMode = 'login'; open = false" class="w-full text-center border border-gray-300 dark:border-zinc-700 py-2 rounded-md font-medium transition-colors">Login</button>
                    <button @click="showAuthModal = true; authMode = 'register'; open = false" class="w-full text-center bg-brand-900 text-white py-2 rounded-md font-bold transition-transform active:scale-95">Daftar Sekarang</button>
                 @endauth
            </div>
        </div>
    </div>
    
    <!-- Auth Modal -->
    <x-login-modal />
</nav>
