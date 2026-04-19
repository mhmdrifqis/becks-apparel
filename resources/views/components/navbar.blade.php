<nav x-data="{ 
        isOpen: false, 
        isScrolled: false,
        isHeroPage: {{ request()->routeIs(['home', 'catalog.index', 'visi-misi', 'portfolio']) ? 'true' : 'false' }},
        cartCount: {{ Auth::check() ? Auth::user()->cartItems()->sum('quantity') : 0 }},
        orderCount: {{ Auth::check() ? Auth::user()->activeOrdersCount() : 0 }},
        notifCount: {{ Auth::check() ? Auth::user()->unreadNotifications()->count() : 0 }},
        get isTransparent() {
            return this.isHeroPage && !this.isScrolled;
        },
        init() {
            window.addEventListener('cart-updated', () => this.fetchCounts());
            if ({{ Auth::check() ? 'true' : 'false' }}) {
                setInterval(() => this.fetchCounts(), 30000);
            }
        },
        async fetchCounts() {
            try {
                const res = await fetch('{{ route('cart.counts') }}');
                const data = await res.json();
                this.cartCount = data.cart;
                this.orderCount = data.orders;
                this.notifCount = data.notifications;
            } catch (e) { console.error('Failed to fetch counts', e); }
        }
    }" 
    x-init="init()"
    @scroll.window="isScrolled = (window.pageYOffset > 20)"
    :class="{ 
        'bg-white/90 dark:bg-zinc-950/90 backdrop-blur-xl shadow-xl py-4': !isTransparent, 
        'bg-transparent py-8': isTransparent 
    }"
    class="fixed w-full z-50 transition-all duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group text-decoration-none">
                    <x-application-logo class="h-10 w-auto transition-transform duration-300 group-hover:scale-110" 
                        ::class="isTransparent ? 'text-white' : 'text-brand-900 dark:text-brand-400'" />
                    <span class="font-black text-2xl tracking-tighter transition-colors duration-300" 
                        :class="isTransparent ? 'text-white' : 'text-slate-900 dark:text-white'">
                        BECKS<span :class="isTransparent ? 'text-white/80' : 'text-brand-600 dark:text-brand-400'">APPAREL</span>
                    </span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden sm:flex sm:items-center sm:gap-10">
                <div class="flex items-center gap-8">
                    <a href="{{ url('/') }}" :class="isTransparent ? 'text-white/90 hover:text-white' : 'text-slate-600 dark:text-zinc-400 hover:text-brand-900'" class="text-xs font-black uppercase tracking-widest transition-all">Home</a>
                    <a href="{{ route('catalog.index') }}" :class="isTransparent ? 'text-white/90 hover:text-white' : 'text-slate-600 dark:text-zinc-400 hover:text-brand-900'" class="text-xs font-black uppercase tracking-widest transition-all">Katalog</a>
                    <a href="{{ route('customizer') }}" :class="isTransparent ? 'text-white/90 hover:text-white' : 'text-slate-600 dark:text-zinc-400 hover:text-brand-900'" class="text-xs font-black uppercase tracking-widest transition-all">Customizer</a>
                </div>

                <div class="h-6 w-px bg-slate-200 dark:bg-zinc-800 transition-opacity" :class="isTransparent ? 'opacity-20' : 'opacity-100'"></div>
                
                @auth
                    <div class="flex items-center gap-6" x-data="{ userOpen: false }">
                        <!-- Notification & Theme -->
                        <div class="flex items-center gap-2">
                            <button class="relative p-2 transition-colors group" :class="isTransparent ? 'text-white/80 hover:text-white' : 'text-slate-500 hover:text-brand-900'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                <template x-if="notifCount > 0">
                                    <span x-text="notifCount" class="absolute top-1 right-1 flex items-center justify-center min-w-[16px] h-[16px] px-1 bg-red-500 text-white text-[9px] font-black rounded-full border-2 border-white dark:border-zinc-950"></span>
                                </template>
                            </button>
                        </div>

                        <!-- User Profile -->
                        <div class="relative">
                            <button @click="userOpen = !userOpen" @click.away="userOpen = false" class="flex items-center gap-3 group focus:outline-none">
                                <div class="hidden md:flex flex-col text-right leading-none">
                                    <span class="text-[10px] font-black uppercase tracking-wider mb-0.5 transition-colors" :class="isTransparent ? 'text-white/60' : 'text-slate-400'">Halo,</span>
                                    <span class="text-xs font-black uppercase tracking-widest transition-colors" :class="isTransparent ? 'text-white' : 'text-slate-900 dark:text-white'">{{ Auth::user()->name }}</span>
                                </div>
                                <div class="relative">
                                    @if(Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                             class="h-10 w-10 rounded-full object-cover border-2 border-brand-500/50 group-hover:border-brand-500 transition-all shadow-lg" 
                                             alt="{{ Auth::user()->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-brand-900 flex items-center justify-center border-2 border-brand-500/50 group-hover:border-brand-500 transition-all shadow-lg">
                                            <span class="text-white font-black text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-green-500 border-2 border-white dark:border-zinc-950 rounded-full shadow-sm"></div>
                                </div>
                            </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-64 rounded-3xl bg-white dark:bg-zinc-900 shadow-2xl border border-gray-100 dark:border-zinc-800 py-3 z-50 overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-100 dark:border-zinc-800 mb-2">
                                <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-black">Portal Pelanggan</p>
                            </div>

                            <a href="{{ route('customer.cart.index') }}" class="flex items-center justify-between px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-brand-950/30 hover:text-brand-900 dark:hover:text-brand-400 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    <span class="font-bold">Keranjang Saya</span>
                                </div>
                                <template x-if="cartCount > 0">
                                    <span x-text="cartCount" class="flex items-center justify-center min-w-[20px] h-[20px] px-1.5 bg-brand-900 text-white text-[10px] font-black rounded-full"></span>
                                </template>
                            </a>

                            <a href="{{ route('customer.orders') }}" class="flex items-center justify-between px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-brand-950/30 hover:text-brand-900 dark:hover:text-brand-400 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                
                                    <span class="font-bold">Pesanan Saya</span>
                                </div>
                                <template x-if="orderCount > 0">
                                    <span x-text="orderCount" class="flex items-center justify-center min-w-[20px] h-[20px] px-1.5 bg-amber-500 text-white text-[10px] font-black rounded-full"></span>
                                </template>
                            </a>
                            <div class="h-px bg-gray-100 dark:border-zinc-800 my-2 mx-5"></div>

                            <a href="{{ route('customer.designs') }}" class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-brand-950/30 hover:text-brand-900 dark:hover:text-brand-400 transition-colors">
                                <svg class="w-5 h-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    <span class="font-bold">Desain Saya</span>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-brand-950/30 hover:text-brand-900 dark:hover:text-brand-400 transition-colors">
                                <svg class="w-5 h-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span class="font-bold">Pengaturan</span>
                            </a>
                            
                            <div class="mt-2 pt-2 border-t border-gray-100 dark:border-zinc-800">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors font-bold text-left">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        <span>Log Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
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
                <button @click="isOpen = !isOpen" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': isOpen, 'inline-flex': !isOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !isOpen, 'inline-flex': isOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="isOpen" 
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
                    <div class="flex items-center gap-4 px-3 py-4 bg-gray-50 dark:bg-zinc-800/50 rounded-2xl mb-4">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                 class="h-12 w-12 rounded-full object-cover border-2 border-brand-500" 
                                 alt="{{ Auth::user()->name }}">
                        @else
                            <div class="h-12 w-12 rounded-full bg-brand-900 flex items-center justify-center border-2 border-brand-500">
                                <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Halo,</p>
                            <p class="text-base font-black text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('customer.cart.index') }}" class="flex items-center justify-between px-3 py-3 text-base font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                <span>Keranjang Saya</span>
                            </div>
                            <template x-if="cartCount > 0">
                                <span x-text="cartCount" class="flex items-center justify-center min-w-[22px] h-[22px] px-1.5 bg-brand-900 text-white text-[11px] font-black rounded-full"></span>
                            </template>
                        </a>
                        <a href="{{ route('customer.orders') }}" class="flex items-center justify-between px-3 py-3 text-base font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 group">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span>Monitor Pesanan</span>
                            </div>
                            <template x-if="orderCount > 0">
                                <span x-text="orderCount" class="flex items-center justify-center min-w-[22px] h-[22px] px-1.5 bg-amber-500 text-white text-[11px] font-black rounded-full"></span>
                            </template>
                        </a>
                        <a href="{{ route('customer.designs') }}" class="block px-3 py-3 text-base font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Design Saya
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-3 text-base font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Pengaturan Profil
                        </a>
                        <div class="pt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-3 py-3 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 rounded-xl font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                 @else
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showAuthModal = true; authMode = 'login'; open = false" class="w-full text-center border border-gray-300 dark:border-zinc-700 py-3 rounded-xl font-medium transition-colors">Login</button>
                        <button @click="showAuthModal = true; authMode = 'register'; open = false" class="w-full text-center bg-brand-900 text-white py-3 rounded-xl font-bold transition-transform active:scale-95">Daftar</button>
                    </div>
                 @endauth
            </div>
        </div>
    </div>
    
    <!-- Auth Modal -->
    <x-login-modal />
</nav>
