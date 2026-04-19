@extends('layouts.main')

@section('title', $package->name . ' - Becks Apparel')

@section('content')
<div x-data="{}" class="bg-white min-h-screen pt-20 pb-24 md:pb-0">
    <div class="max-w-7xl mx-auto px-4 py-8 md:py-16">
        
        <!-- Breadcrumbs & Back -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <nav class="flex items-center gap-2 text-[10px] md:text-xs font-bold uppercase tracking-widest text-slate-400">
                <a href="{{ route('catalog.index') }}" class="hover:text-brand-900 transition-colors">Katalog</a>
                <span>/</span>
                <span class="text-slate-900">{{ $package->name }}</span>
            </nav>
            
            <button @click="window.history.back()" class="inline-flex items-center gap-2 text-[10px] md:text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-brand-900 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">
            
            <!-- left: Image Section -->
            <div class="space-y-6">
                <div class="aspect-[4/5] bg-slate-50 rounded-[3rem] overflow-hidden border border-slate-100 shadow-2xl shadow-slate-200/50 group relative">
                    @if($package->image_path)
                        <img src="{{ asset($package->image_path) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                        </div>
                    @endif
                    
                    <!-- Zoom Button Hint -->
                    <div class="absolute bottom-8 right-8 bg-white/80 backdrop-blur-md p-4 rounded-full shadow-lg border border-white/20 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    </div>
                </div>

                <!-- thumbnails (Placeholder) -->
                <div class="grid grid-cols-4 gap-4">
                    @for($i = 0; $i < 4; $i++)
                    <div class="aspect-square bg-slate-50 rounded-2xl border border-slate-100 opacity-50 hover:opacity-100 cursor-pointer transition-opacity"></div>
                    @endfor
                </div>
            </div>

            <!-- Right: Info Section -->
            <div class="flex flex-col">
                <div class="mb-4">
                    <span class="inline-block px-4 py-1.5 bg-brand-900/5 text-brand-900 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] mb-4">
                        {{ $package->category }} Package
                    </span>
                    <h1 class="text-3xl md:text-5xl font-bold text-slate-900 mb-2 uppercase tracking-tight leading-tight">
                        {{ $package->name }}
                    </h1>
                    <div class="flex items-center gap-4 text-2xl md:text-3xl font-bold text-brand-900">
                        <span>Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
                        <span class="text-xs text-slate-400 font-medium uppercase tracking-widest bg-slate-50 px-3 py-1 rounded">Mulai Dari</span>
                    </div>
                </div>

                <div class="h-px bg-slate-100 w-full my-8"></div>

                <div class="space-y-6 mb-10">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-brand-900"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                        Deskripsi Paket
                    </h3>
                    <p class="text-slate-500 leading-relaxed text-sm md:text-base">
                        {{ $package->description }}
                    </p>
                </div>

                <!-- Features List -->
                <div class="space-y-4 mb-12">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-brand-900"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Fitur & Kelengkapan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($package->features ?? [] as $feature)
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-brand-900/30 transition-colors">
                            <div class="w-6 h-6 rounded-full bg-brand-900 flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <span class="text-xs md:text-sm font-medium text-slate-700">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Desktop CTA -->
                <div class="hidden md:flex gap-4">
                    <button 
                        @click="
                            fetch('{{ route('cart.add', $package->slug) }}', { 
                                method: 'POST', 
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } 
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.success) {
                                    $dispatch('cart-updated');
                                    window.location.href = '{{ route('customer.cart.index') }}';
                                }
                            })
                        "
                        class="flex-1 bg-white text-brand-900 border-2 border-brand-900 px-10 py-5 rounded-3xl text-sm font-bold uppercase tracking-widest text-center hover:bg-brand-50 transition-all active:scale-95"
                    >
                        Tambah ke Keranjang
                    </button>
                    <a href="{{ route('customizer', ['package' => $package->slug]) }}" class="flex-[2] bg-brand-900 text-white px-10 py-5 rounded-3xl text-sm font-bold uppercase tracking-widest text-center shadow-2xl shadow-brand-900/20 hover:bg-brand-800 hover:-translate-y-1 transition-all active:translate-y-0 text-decoration-none">
                        Buat Desain Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sticky CTA -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-xl border-t border-slate-100 z-50 md:hidden">
        <div class="flex items-center gap-4">
            <div class="flex-1 text-left">
                <span class="block text-[8px] text-slate-400 uppercase font-bold tracking-widest">Harga Paket</span>
                <span class="text-lg font-bold text-brand-900">Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
            </div>

            <button 
                @click="
                    fetch('{{ route('cart.add', $package->slug) }}', { 
                        method: 'POST', 
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } 
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            $dispatch('cart-updated');
                            window.location.href = '{{ route('customer.cart.index') }}';
                        }
                    })
                "
                class="w-14 h-14 bg-slate-100 text-brand-900 rounded-2xl flex items-center justify-center active:scale-90 transition-all"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </button>

            <a href="{{ route('customizer', ['package' => $package->slug]) }}" class="bg-brand-900 text-white px-8 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-brand-900/20 active:scale-95 transition-all text-decoration-none">
                Kustomisasi
            </a>
        </div>
    </div>
</div>
@endsection
