@extends('layouts.main')

@section('title', 'Monitor Pesanan - Becks Apparel')

@section('content')
    <div class="min-h-screen bg-[#fdfbf7] py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
            <!-- Header -->
            <div class="mb-12 md:mb-20">
                <h1 class="text-4xl md:text-7xl font-black text-brand-900 tracking-tighter uppercase mb-4">
                    Pesanan <span class="text-brand-600">Saya</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl">
                    Pantau status produksi dan rincian pesanan jersey kustom Anda secara real-time.
                </p>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 md:p-12 text-center">
                    <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-full bg-brand-50 text-brand-900">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h2>
                    <p class="text-gray-500 mb-8 max-w-sm mx-auto">
                        Anda belum memiliki riwayat pesanan. Mulai rancang jersey kustom Anda sekarang!
                    </p>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand-900 text-white rounded-full font-black uppercase tracking-widest text-sm hover:bg-brand-800 transition-all shadow-lg active:scale-95">
                        Lihat Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
