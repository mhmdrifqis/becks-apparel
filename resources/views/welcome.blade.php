@extends('layouts.main')

@section('title', 'Premium Sports Apparel & Jersey Customizer')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-screen flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset('assets/images/hero_banner.png') }}" 
                alt="Becks Apparel Hero" 
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/70 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20">
            <div class="max-w-2xl">
                <span class="inline-block px-4 py-1.5 rounded-full bg-brand-900/30 border border-brand-500/30 text-brand-400 font-bold text-xs tracking-widest uppercase mb-6 animate-fade-in shadow-lg backdrop-blur-sm">
                    Exclusive Sportswear Engine
                </span>
                <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tighter leading-[0.9] mb-6">
                    DEFINISI BARU <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-green-300">JERSEY PREMIUM.</span>
                </h1>
                <p class="text-gray-300 text-lg md:text-xl mb-10 leading-relaxed max-w-lg">
                    Wujudkan desain impian Anda dengan fitur kustomisasi interaktif. Kualitas bahan atlet profesional, harga yang tetap rasional.
                </p>
                <div class="flex flex-col sm:flex-row gap-5">
                    <a href="{{ route('customizer') }}" class="px-8 py-4 bg-brand-900 border border-brand-700 text-white rounded-full font-bold text-lg hover:bg-brand-800 hover:scale-105 transition-all shadow-xl shadow-brand-950/50 flex items-center justify-center gap-2 text-decoration-none">
                        Design Now
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('catalog.index') }}" class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full font-bold text-lg hover:bg-white/20 transition-all flex items-center justify-center text-decoration-none">
                        View Catalog
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroller Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 hidden md:block animate-bounce">
            <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center pt-2">
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="py-24 bg-white dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-decoration-none">
            <div class="mb-20">
                <h2 class="text-3xl md:text-5xl font-bold mb-4 tracking-tight">Pilih Paket <span class="text-brand-900 dark:text-brand-400">Jersey Anda</span></h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Tersedia berbagai pilihan paket kualitas sesuai kebutuhan tim Anda, dari kasta amatir hingga pro.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @php
                    $packages = [
                        ['id' => 'A', 'slug' => 'paket-a-standard', 'price' => '90.000', 'desc' => 'Non-printing, Logo/Nameset DTF', 'features' => ['Bahan Standar', 'Logo DTF', 'Nameset DTF', 'Non-printing']],
                        ['id' => 'B', 'slug' => 'paket-b-sleeve-print', 'price' => '110.000', 'desc' => 'Lengan Printing, Logo/Nameset DTF', 'features' => ['Bahan Standar', 'Lengan Full Print', 'Logo DTF', 'Nameset DTF']],
                        ['id' => 'C', 'slug' => 'paket-c-front-print', 'price' => '130.000', 'desc' => 'Jersey Full Printing, Celana Non-printing', 'features' => ['Bahan Premium', 'Jersey Full Print', 'Logo Sublime', 'Nameset Sublime']],
                        ['id' => 'D', 'slug' => 'paket-d-full-printing', 'price' => '160.000', 'desc' => 'Jersey & Celana Full Printing', 'features' => ['Bahan Premium', 'Jersey Full Print', 'Celana Full Print', 'Full Sublime']],
                        ['id' => 'E', 'slug' => 'paket-e-professional', 'price' => '170.000', 'desc' => 'Full Printing + Logo/Sponsor DTF', 'features' => ['Bahan Premium', 'Full Print Custom', 'Logo DTF Eksklusif', 'Sponsor DTF']],
                    ];
                @endphp

                @foreach($packages as $pkg)
                    <div class="group relative bg-gray-50 dark:bg-zinc-900/50 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-brand-900/20 hover:border-brand-500/30">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-100 transition-opacity">
                            <span class="text-4xl font-black text-brand-900 dark:text-brand-400">{{ $pkg['id'] }}</span>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Paket {{ $pkg['id'] }}</h3>
                        <div class="text-2xl font-black text-brand-900 dark:text-brand-400 mb-4">Rp {{ $pkg['price'] }}</div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">{{ $pkg['desc'] }}</p>
                        <ul class="space-y-3 mb-8 text-left">
                            @foreach($pkg['features'] as $feat)
                                <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-zinc-300">
                                    <svg class="w-4 h-4 text-brand-600 dark:text-brand-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    {{ $feat }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('catalog.show', $pkg['slug']) }}" class="block w-full py-3 rounded-xl bg-white dark:bg-zinc-800 border dark:border-zinc-700 font-bold text-sm tracking-wide group-hover:bg-brand-900 group-hover:text-white transition-all shadow-sm text-center text-decoration-none">Pilih Paket</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Customizer CTA Section -->
    <section class="py-24 bg-brand-950 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-900/20 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-green-500/10 blur-[120px] rounded-full"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-zinc-900 to-black rounded-[2.5rem] p-8 md:p-20 border border-white/10 shadow-3xl text-center md:text-left relative overflow-hidden">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-8">
                            JADILAH DESAINER <br/>
                            <span class="text-brand-400 uppercase italic tracking-widest">TIM SENDIRI.</span>
                        </h2>
                        <p class="text-zinc-400 text-lg mb-10 max-w-md">
                            Coba fitur kustomisasi interaktif kami. Pilih logo, motif, hingga gradient warna secara real-time. Rasakan pengalaman mendesain tanpa batas.
                        </p>
                        <a href="{{ route('customizer') }}" class="inline-flex px-10 py-5 bg-brand-900 text-white rounded-full font-black text-lg hover:bg-brand-800 hover:scale-110 transition-all shadow-xl shadow-brand-950/50 uppercase tracking-tighter italic text-decoration-none">
                            Buka Online Customizer
                        </a>
                    </div>
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-brand-600 to-green-400 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative bg-zinc-900 border border-white/5 rounded-2xl overflow-hidden shadow-2xl">
                            <img 
                                src="https://images.unsplash.com/photo-1542291026-7eec264c2741?q=80&w=1200" 
                                alt="Customizer Preview" 
                                class="w-full h-[400px] object-cover mix-blend-overlay opacity-50 transition-all duration-700 group-hover:scale-110 group-hover:rotate-2"
                            />
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-24 h-24 bg-brand-900/80 rounded-full flex items-center justify-center border-4 border-white/20 animate-pulse">
                                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Other Categories Section -->
    <section class="py-24 dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="text-left">
                    <h2 class="text-3xl md:text-5xl font-bold mb-4">Produk <span class="text-brand-900 dark:text-brand-400">Lainnya</span></h2>
                    <p class="text-gray-600 dark:text-zinc-400">Selain jersey, kami juga menyediakan apparel pendukung lainnya.</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="font-bold text-brand-900 dark:text-brand-400 flex items-center gap-2 hover:gap-4 transition-all text-decoration-none">
                    Lihat Semua Produk <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @php
                    $categories = [
                        ['name' => 'Jacket', 'price' => 'Mulai 155k', 'img' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop', 'desc' => 'Custom jacket full printing & kombinasi bahan.'],
                        ['name' => 'Kaos 24s/30s', 'price' => 'Mulai 60k', 'img' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?q=80&w=800&auto=format&fit=crop', 'desc' => 'Kaos bahan katun berkualitas tinggi untuk aktivitas harian.'],
                        ['name' => 'Kemeja', 'price' => 'Mulai 80k', 'img' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?q=80&w=800&auto=format&fit=crop', 'desc' => 'Kemeja custom dengan desain formal maupun casual.'],
                    ];
                @endphp

                @foreach($categories as $cat)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden rounded-[2rem] h-80 mb-6 bg-zinc-800">
                            <img 
                                src="{{ $cat['img'] }}" 
                                alt="{{ $cat['name'] }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 group-hover:rotate-1"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                            <div class="absolute bottom-6 left-6">
                                <p class="text-brand-400 text-sm font-black tracking-widest uppercase mb-1">{{ $cat['price'] }}</p>
                                <h3 class="text-2xl font-bold text-white">{{ $cat['name'] }}</h3>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-zinc-400 text-sm leading-relaxed">{{ $cat['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 1s ease-out forwards;
        }
    </style>
@endsection
