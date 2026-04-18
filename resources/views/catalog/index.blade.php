@extends('layouts.main')

@section('title', 'Katalog Paket - Becks Apparel')

@section('content')
<div class="bg-slate-50 min-h-screen pt-20">
    <!-- Hero Section -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 py-12 md:py-20 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-slate-900 mb-4 uppercase tracking-tight">Katalog Paket Premium</h1>
            <p class="text-slate-500 max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
                Pilih paket jersey terbaik yang sesuai dengan kebutuhan tim Anda. Dari kualitas standar hingga profesional dengan fitur full printing.
            </p>
        </div>
    </div>

    <!-- Catalog Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div x-data="{ activeCategory: 'all' }" class="space-y-12">
            
            <!-- Category Filters -->
            <div class="flex flex-wrap justify-center gap-2 md:gap-4 mb-8">
                <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-brand-900 text-white shadow-lg' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-500'" class="px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest transition-all">Semua</button>
                <button @click="activeCategory = 'jersey'" :class="activeCategory === 'jersey' ? 'bg-brand-900 text-white shadow-lg' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-500'" class="px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest transition-all">Jersey</button>
                <button @click="activeCategory = 'jacket'" :class="activeCategory === 'jacket' ? 'bg-brand-900 text-white shadow-lg' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-500'" class="px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest transition-all">Jaket</button>
                <button @click="activeCategory = 'tshirt'" :class="activeCategory === 'tshirt' ? 'bg-brand-900 text-white shadow-lg' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-500'" class="px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest transition-all">Kaos</button>
            </div>

            <!-- Product Grids -->
            @foreach($packages as $category => $items)
            <div x-show="activeCategory === 'all' || activeCategory === '{{ $category }}'" x-transition class="space-y-8">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-bold text-slate-800 uppercase tracking-widest">{{ ucfirst($category) }}</h2>
                    <div class="h-px bg-slate-200 flex-1"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach($items as $package)
                    <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden group hover:shadow-2xl hover:shadow-brand-900/5 transition-all duration-500 flex flex-col h-full">
                        <!-- Image Container -->
                        <div class="aspect-[4/5] bg-slate-100 relative overflow-hidden text-decoration-none">
                            @if($package->image_path)
                                <img src="{{ asset($package->image_path) }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            <div class="absolute top-6 left-6">
                                <span class="bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest text-brand-900 shadow-sm border border-slate-100">
                                    {{ $package->category }}
                                </span>
                            </div>

                            <!-- Floating Action -->
                            <div class="absolute inset-0 bg-brand-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <a href="{{ route('catalog.show', $package->slug) }}" class="bg-white text-brand-900 px-8 py-3 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-brand-50 transition-colors shadow-2xl">Lihat Detail</a>
                            </div>
                        </div>

                        <!-- Info Content -->
                        <div class="p-8 flex flex-col flex-1">
                            <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-brand-900 transition-colors">{{ $package->name }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-2">{{ $package->description }}</p>
                            
                            <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-6">
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">Mulai Dari</span>
                                    <span class="text-xl font-bold text-brand-900">Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('catalog.show', $package->slug) }}" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center hover:bg-brand-900 hover:text-white transition-all text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection
