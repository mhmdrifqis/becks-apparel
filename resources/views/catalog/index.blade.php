@extends('layouts.main')

@section('title', 'Katalog Paket - Becks Apparel')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- Hero Section: Full height & immersive like Portfolio -->
    <!-- Hero Section: Compact & Immersive -->
    <section class="relative h-[40vh] md:h-[50vh] flex items-center overflow-hidden bg-brand-950">
        <!-- Background Layer -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset('assets/images/katalog/hero-katalog.png') }}" 
                alt="Catalog Hero" 
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-[#06402B] via-[#06402B]/80 to-[#06402B]/60"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 mix-blend-overlay"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-white/80 font-bold text-[10px] tracking-[0.2em] uppercase mb-4 backdrop-blur-md">
                    Premium Catalog
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-none uppercase">
                    Pilihan <span class="text-brand-400">Terbaik.</span>
                </h1>
            </div>
        </div>
    </section>

    <!-- Catalog Content -->
    <div class="max-w-7xl mx-auto px-4 py-12" x-data="{ activeCategory: 'all' }">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-64 space-y-8 flex-shrink-0">
                <div>
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Kategori Produk</h3>
                    <div class="space-y-2">
                        <button @click="activeCategory = 'all'" 
                                :class="activeCategory === 'all' ? 'bg-brand-900 text-white shadow-lg shadow-brand-900/20' : 'text-slate-600 hover:bg-slate-100'" 
                                class="w-full text-left px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all flex items-center justify-between group">
                            Semua Produk
                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        @php $categories = ['jersey' => 'Jersey', 'jacket' => 'Jaket', 'tshirt' => 'Kaos', 'kemeja' => 'Kemeja']; @endphp
                        @foreach($categories as $key => $label)
                        <button @click="activeCategory = '{{ $key }}'" 
                                :class="activeCategory === '{{ $key }}' ? 'bg-brand-900 text-white shadow-lg shadow-brand-900/20' : 'text-slate-600 hover:bg-slate-100'" 
                                class="w-full text-left px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all flex items-center justify-between group">
                            {{ $label }}
                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="p-6 bg-brand-50 rounded-[2rem] border border-brand-100">
                    <p class="text-[10px] font-black text-brand-900 uppercase tracking-widest mb-2">Butuh Bantuan?</p>
                    <p class="text-xs text-brand-800/60 leading-relaxed mb-4">Konsultasikan kebutuhan tim Anda dengan tim ahli kami secara gratis.</p>
                    <a href="#" class="inline-block text-[10px] font-black text-brand-900 uppercase tracking-widest border-b-2 border-brand-900 pb-1">Hubungi WhatsApp</a>
                </div>
            </aside>

            <!-- Product Grid Area -->
            <main class="flex-1">
                @foreach($packages as $category => $items)
                <div x-show="activeCategory === 'all' || activeCategory === '{{ $category }}'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mb-16">
                    
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em]">{{ $categories[$category] ?? ucfirst($category) }}</h2>
                        <div class="h-px bg-slate-100 flex-1"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ count($items) }} Models</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        @foreach($items as $package)
                        <a href="{{ route('catalog.show', $package->slug) }}" class="group block bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden hover:shadow-2xl hover:shadow-slate-200 transition-all duration-500 text-decoration-none">
                            <!-- Image Container -->
                            <div class="aspect-[4/5] bg-slate-50 relative overflow-hidden">
                                @if($package->images && count($package->images) > 0)
                                    @php $imgPath = $package->images[0]; $src = str_starts_with($imgPath, 'assets/') ? asset($imgPath) : Storage::url($imgPath); @endphp
                                    <img src="{{ $src }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                
                                <!-- Hover Overlay -->
                                <div class="absolute inset-x-4 bottom-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                    <div class="bg-white/90 backdrop-blur-md p-4 rounded-2xl text-center shadow-xl">
                                        <p class="text-[10px] font-black text-brand-900 uppercase tracking-widest">Detail Produk & Kustomisasi</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight group-hover:text-brand-900 transition-colors line-clamp-1 mb-1">{{ $package->name }}</h3>
                                <div class="flex items-center justify-between items-end">
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Mulai Dari</p>
                                        <p class="text-lg font-black text-brand-900 tracking-tight">Rp {{ number_format($package->base_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-brand-900 group-hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </main>
        </div>
    </div>

        </div>
    </div>
</div>
@endsection
