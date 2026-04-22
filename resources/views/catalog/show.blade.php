@extends('layouts.main')

@section('title', $package->name . ' - Becks Apparel')

@section('content')
<div x-data="{ 
    qty: 1, 
    selectedMaterialId: '{{ $materials->first()?->id ?? 0 }}',
    materials: @js($materials),
    openDetail: false,
    {{-- Carousel State --}}
    activeSlide: 0,
    slidesCount: {{ count($package->images ?: []) > 0 ? count($package->images) : 1 }},
    autoplay() {
        setInterval(() => {
            this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
        }, 5000);
    },
    get unitPrice() {
        let base = {{ $package->base_price }};
        let material = this.materials.find(m => m.id == this.selectedMaterialId);
        let add = material ? parseFloat(material.additional_price) : 0;
        return base + add;
    },
    get totalPrice() {
        return this.unitPrice * this.qty;
    },
    get estimation() {
        if (this.qty < 24) return '7-10 Hari Kerja';
        if (this.qty < 50) return '10-14 Hari Kerja';
        return '14-21 Hari Kerja';
    },
    async addToCart(redirect = false) {
        if (!@js(auth()->check())) {
            $dispatch('open-auth-modal');
            return;
        }

        if (!this.selectedMaterialId || this.selectedMaterialId == 0) {
            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Silakan pilih jenis bahan dahulu!', type: 'warning' } }));
            return;
        }
        
        try {
            let response = await fetch('{{ route('cart.add', $package->slug) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    material_id: this.selectedMaterialId,
                    quantity: this.qty
                })
            });
            
            let data = await response.json();
            if (data.success) {
                $dispatch('cart-updated');
                if (redirect && data.cart_item_id) {
                    window.location.href = '{{ route('customer.cart.index') }}?select=' + data.cart_item_id;
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Pesanan ditambahkan ke keranjang ✨', type: 'success' } }));
                }
            } else {
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.message, type: 'error' } }));
            }
        } catch (e) {
            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal menghubungi server', type: 'error' } }));
        }
    }
}" x-init="autoplay()" class="bg-white dark:bg-zinc-950 min-h-screen pt-20 pb-32 md:pb-12">
    <div class="max-w-7xl mx-auto px-4 py-8 md:py-16">
        
        <!-- Breadcrumbs: Minimalist -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
            <a href="/" class="hover:text-brand-900">Becks Apparel</a>
            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('catalog.index') }}" class="hover:text-brand-900">Katalog</a>
            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-900 font-medium truncate">{{ $package->name }}</span>
        </nav>

        <div class="bg-white rounded-[2rem] shadow-sm flex flex-col md:flex-row gap-0 overflow-hidden border border-slate-100">
            
            <!-- 1. LEFT: Gallery (Shopee Layout Style) -->
            <div class="w-full md:w-[450px] lg:w-[480px] p-6 bg-white border-r border-slate-50 flex-shrink-0">
                <div class="aspect-square bg-slate-50 mb-6 overflow-hidden rounded-2xl">
                    @php $imgList = $package->images ?: ['assets/images/placeholder.png']; @endphp
                    @foreach($imgList as $i => $imgPath)
                    @php $src = str_starts_with($imgPath, 'assets/') ? asset($imgPath) : Storage::url($imgPath); @endphp
                    <img x-show="activeSlide === {{ $i }}" 
                         x-transition:enter="transition ease-in-out duration-300"
                         src="{{ $src }}" 
                         class="w-full h-full object-contain p-4" 
                         alt="Main Image">
                    @endforeach
                </div>
                
                <!-- Thumbnails Horizontal -->
                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($imgList as $i => $imgPath)
                    @php $src = str_starts_with($imgPath, 'assets/') ? asset($imgPath) : Storage::url($imgPath); @endphp
                    <button @click="activeSlide = {{ $i }}" 
                            class="w-20 h-20 flex-shrink-0 border-2 transition-all p-1 rounded-xl"
                            :class="activeSlide === {{ $i }} ? 'border-brand-900 bg-brand-50' : 'border-slate-100 hover:border-slate-200'">
                        <img src="{{ $src }}" class="w-full h-full object-cover rounded-lg" alt="Thumb">
                    </button>
                    @endforeach
                </div>

                <!-- Size Chart Section -->
                <div class="mt-12 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Size Chart (Standard)
                    </h3>
                    <div class="overflow-hidden rounded-xl border border-slate-200">
                        <table class="w-full text-center text-[11px] border-collapse bg-white">
                            <thead>
                                <tr class="bg-black text-white">
                                    <th class="py-2.5 font-black uppercase border-r border-slate-800">Size</th>
                                    <th class="py-2.5 font-black uppercase border-r border-slate-800">L (Lebar)</th>
                                    <th class="py-2.5 font-black uppercase">P (Panjang)</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-600 font-bold">
                                <tr class="border-b border-slate-100">
                                    <td class="py-2 border-r border-slate-100 bg-slate-50/50">S</td>
                                    <td class="py-2 border-r border-slate-100">48 CM</td>
                                    <td class="py-2">68 CM</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-2 border-r border-slate-100 bg-slate-50/50">M</td>
                                    <td class="py-2 border-r border-slate-100">50 CM</td>
                                    <td class="py-2">70 CM</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-2 border-r border-slate-100 bg-slate-50/50">L</td>
                                    <td class="py-2 border-r border-slate-100">52 CM</td>
                                    <td class="py-2">72 CM</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-2 border-r border-slate-100 bg-slate-50/50">XL</td>
                                    <td class="py-2 border-r border-slate-100">54 CM</td>
                                    <td class="py-2">74 CM</td>
                                </tr>
                                <tr>
                                    <td class="py-2 border-r border-slate-100 bg-slate-50/50">XXL</td>
                                    <td class="py-2 border-r border-slate-100">56 CM</td>
                                    <td class="py-2">76 CM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-3 text-[9px] text-slate-400 font-medium">* Toleransi ukuran ± 1-2 cm</p>
                </div>
            </div>

            <!-- 2. RIGHT: Product Info -->
            <div class="flex-1 p-6 md:p-10 space-y-8">
                <!-- Title -->
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-800 leading-none uppercase tracking-tight">{{ $package->name }}</h1>
                    <div class="flex items-center gap-2 mt-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <span>Pilihan Terbaik</span>
                        <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                        <span>{{ $package->category }}</span>
                    </div>
                </div>

                <!-- Price Section: Brand Color -->
               <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <div class="flex flex-col gap-1 md:gap-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Mulai Dari</span>
                        <span class="text-4xl md:text-5xl font-black text-brand-900 tracking-tighter" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(unitPrice)"></span>
                    </div>
                </div>
                <!-- Info & Variants -->
                <div class="space-y-8 pt-4">
                    <!-- Material Choice -->
                    <div class="flex flex-col lg:flex-row lg:items-start">
                        <span class="w-32 text-xs font-black text-slate-400 uppercase tracking-widest pt-2">Pilihan Bahan</span>
                        <div class="flex-1">
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($materials as $material)
                                <button @click="selectedMaterialId = '{{ $material->id }}'" 
                                        class="p-4 text-[10px] font-black uppercase tracking-widest border-2 rounded-2xl transition-all relative text-left group"
                                        :class="selectedMaterialId == '{{ $material->id }}' ? 'border-brand-900 bg-brand-50 text-brand-900 shadow-lg shadow-brand-900/5' : 'border-slate-100 text-slate-500 hover:border-slate-200 bg-white'">
                                    {{ $material->name }}
                                    <div x-show="selectedMaterialId == '{{ $material->id }}'" class="absolute bottom-1 right-1 text-brand-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <span class="w-32 text-xs font-black text-slate-400 uppercase tracking-widest">Jumlah</span>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1 p-1 bg-slate-50 rounded-2xl border border-slate-100">
                                <button @click="if(qty > 1) qty--" class="w-10 h-10 flex items-center justify-center bg-white text-slate-400 rounded-xl hover:text-brand-900 transition-colors border border-slate-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                                </button>
                                <input type="number" x-model="qty" class="w-12 text-center bg-transparent border-none text-lg font-black p-0 focus:ring-0 text-slate-800" readonly>
                                <button @click="qty++" class="w-10 h-10 flex items-center justify-center bg-white text-slate-400 rounded-xl hover:text-brand-900 transition-colors border border-slate-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                            <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest leading-relaxed">
                                * Minimal Pesanan<br/>12 Pcs
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Desktop Action Buttons -->
                <div class="pt-8 flex flex-wrap gap-4 hidden md:flex">
                    <button @click="addToCart(false)" class="flex-1 h-16 flex items-center justify-center gap-2 border-2 border-brand-900 bg-brand-50 text-brand-900 font-black uppercase tracking-widest text-[10px] rounded-3xl hover:bg-brand-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Masukkan Keranjang
                    </button>
                    <a href="{{ route('customizer', ['package' => $package->slug]) }}" class="flex-[1.2] h-16 flex items-center justify-center gap-2 bg-black text-white font-black uppercase tracking-widest text-[11px] rounded-3xl hover:bg-slate-800 transition-all text-decoration-none shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Desain Sekarang
                    </a>
                    <button @click="addToCart(true)" class="flex-1 h-16 bg-brand-900 text-white font-black uppercase tracking-widest text-[10px] rounded-3xl hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/20">
                        Beli Sekarang
                    </button>
                </div>
                
            </div>
        </div>

        <!-- Spesifikasi Section -->
        <div class="mt-12 bg-white p-8 md:p-12 border border-slate-100 shadow-sm rounded-[2.5rem]">
            <div class="mb-10 pb-4 border-b border-slate-100 flex items-center gap-4">
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em]">Spesifikasi & Detail Produk</h2>
                <div class="h-px bg-slate-100 flex-1"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <div class="flex items-center py-2">
                        <span class="w-32 md:w-48 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</span>
                        <span class="text-sm font-black text-slate-900 uppercase">{{ $package->category }}</span>
                    </div>
                    <div class="flex items-center py-2">
                        <span class="w-32 md:w-48 text-[10px] font-black text-slate-400 uppercase tracking-widest">Estimasi Selesai</span>
                        <span class="text-sm font-black text-brand-900 uppercase tracking-wider" x-text="estimation"></span>
                    </div>
                </div>

                <div class="pt-2">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-6">Deskripsi Lengkap</h3>
                    <div class="prose prose-sm max-w-none text-slate-500 leading-relaxed font-medium">
                        {{ $package->description }}
                        @if(!empty($package->specification))
                        <div class="mt-8 border-t border-slate-50 pt-8">
                            {!! $package->specification !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. MOBILE BOTTOM ACTIONS (Flat Grid Style - Fixed 3 Items) -->
    <div class="md:hidden fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-slate-100 shadow-lg">
        <div class="grid h-full grid-cols-4 font-medium">
            <!-- Keranjang (Icon + Text) - 1/4 width -->
            <button @click="addToCart(false)" class="inline-flex flex-col items-center justify-center px-2 hover:bg-slate-50 transition-colors text-slate-500 border-r border-slate-50">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span class="text-[8px] font-black uppercase tracking-[0.1em]">Keranjang</span>
            </button>

            <!-- Desain Sekarang (ICON ONLY) - 1/4 width -->
            <a href="{{ route('customizer', ['package' => $package->slug]) }}" 
               class="inline-flex flex-col items-center justify-center gap-1 bg-black text-white hover:bg-slate-900 transition-colors py-2">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span class="text-[8px] font-black uppercase tracking-[0.1em]">Desain</span>               
            </a>

            <!-- Beli Sekarang (TEXT ONLY / PRIMARY) - 2/4 width -->
            <button @click="addToCart(true)" class="col-span-2 inline-flex items-center justify-center bg-brand-900 text-white hover:bg-brand-800 transition-colors border-l border-brand-800/30">
                <span class="text-[10px] font-black uppercase tracking-widest leading-none text-center">Beli Sekarang</span>
            </button>
        </div>
    </div>
</div>
</div>
@endsection
