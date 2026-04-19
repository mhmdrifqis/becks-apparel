@extends('layouts.main')

@section('title', 'Keranjang Saya - Becks Apparel')

@section('content')
<div class="min-h-screen bg-[#fdfbf7] py-20 md:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <!-- Header -->
        <div class="mb-12 md:mb-20 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-4xl md:text-7xl font-black text-brand-900 tracking-tighter uppercase mb-4">
                    Keranjang <span class="text-brand-600">Saya</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl font-medium">
                    Tinjau paket jersey pilihan Anda sebelum lanjut ke proses desain massal.
                </p>
            </div>
        </div>

        @if($cartItems->isEmpty())
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden p-12 md:p-20 text-center">
                <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-full bg-brand-50 text-brand-900">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 uppercase tracking-tight">Keranjang Kosong</h2>
                <p class="text-gray-500 mb-10 max-w-sm mx-auto font-medium">
                    Belum ada produk di keranjang. Silakan pilih paket dari katalog kami.
                </p>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-10 py-5 bg-brand-900 text-white rounded-full font-black uppercase tracking-widest text-sm hover:bg-brand-800 transition-all shadow-lg active:scale-95">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-6">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-gray-100 shadow-lg flex flex-col md:flex-row items-center gap-8 group hover:border-brand-900/20 transition-all">
                            <div class="w-32 h-32 bg-slate-50 rounded-3xl overflow-hidden shrink-0 border border-slate-100 shadow-inner">
                                @if($item->package->image_path)
                                    <img src="{{ asset($item->package->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <span class="inline-block px-3 py-1 bg-brand-50 text-brand-900 rounded-full text-[9px] font-black uppercase tracking-widest mb-2">{{ $item->package->category }}</span>
                                <h3 class="font-black text-xl md:text-2xl text-slate-900 uppercase tracking-tight">{{ $item->package->name }}</h3>
                                <div class="flex items-center justify-center md:justify-start gap-4 mt-2">
                                    <p class="text-brand-900 font-black text-lg">Rp {{ number_format($item->package->base_price, 0, ',', '.') }}</p>
                                    <span class="text-xs text-slate-300 font-bold uppercase tracking-widest">Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <div class="flex flex-row md:flex-col gap-3 w-full md:w-auto">
                                 <a href="{{ route('customizer', ['package' => $item->package->slug]) }}" class="flex-1 px-8 py-4 bg-brand-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest text-center shadow-lg hover:bg-brand-800 transition-all active:scale-95">Kustomisasi</a>
                                 <button class="flex-1 px-8 py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-slate-100 hover:bg-red-50 hover:text-red-500 transition-colors">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-gray-100 shadow-2xl sticky top-32">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-4 mb-10">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 font-medium">{{ $item->package->name }} x{{ $item->quantity }}</span>
                                <span class="text-slate-900 font-bold">Rp {{ number_format($item->package->base_price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="h-px bg-slate-100 w-full mb-8"></div>

                        <div class="mb-10">
                           <p class="text-xs text-slate-400 uppercase font-black tracking-widest mb-1">Total Estimasi</p>
                           <p class="text-4xl font-black text-brand-900 tracking-tighter">Rp {{ number_format($cartItems->sum(fn($i) => $i->package->base_price * $i->quantity), 0, ',', '.') }}</p>
                        </div>

                        <button class="w-full px-12 py-5 bg-brand-900 text-white rounded-full font-black uppercase tracking-widest text-xs hover:bg-brand-800 active:scale-95 transition-all shadow-2xl shadow-brand-900/20">
                            Lanjut ke Desain Massal
                        </button>
                        
                        <p class="text-[10px] text-slate-400 text-center mt-6 font-medium">
                            *Harga di atas adalah estimasi awal paket.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
