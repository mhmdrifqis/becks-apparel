@extends('layouts.main')

@section('title', 'Visi & Misi - Becks Apparel')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- Hero Section: Full height & immersive like Catalog -->
    <section class="relative h-[80vh] flex items-center overflow-hidden bg-brand-950">
        <!-- Background Layer -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset('assets/images/store.png') }}" 
                alt="Vision Hero" 
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-[#06402B] via-[#06402B]/80 to-[#06402B]/60"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 mix-blend-overlay"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20">
            <div class="max-w-3xl">
                <span class="inline-block px-4 py-1.5 rounded-full bg-brand-900/40 border border-brand-500/20 text-brand-400 font-bold text-xs tracking-[0.2em] uppercase mb-8 backdrop-blur-md">
                    Our Purpose
                </span>
                <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter leading-none mb-8">
                    VISI & <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-green-300">MISI KAMI.</span>
                </h1>
                <p class="text-brand-100/60 text-lg md:text-xl mb-12 max-w-xl leading-relaxed italic">
                    "Mempersatukan kultur sepak bola dan gaya hidup dalam setiap jahitan orisinal."
                </p>
            </div>
        </div>

        <!-- Bottom Scroller Info -->
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 hidden md:block">
            <div class="flex items-center gap-3 text-brand-400/40 text-[10px] uppercase font-bold tracking-[0.3em]">
                <span class="w-8 h-px bg-current"></span>
                Scroll to Discover
                <span class="w-8 h-px bg-current"></span>
            </div>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 py-20">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <!-- Vision -->
            <div class="space-y-6">
                <div class="inline-block p-4 bg-brand-100 rounded-2xl text-brand-900 mb-2">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h2 class="text-3xl font-black text-brand-900 uppercase tracking-tight">Visi Kami</h2>
                <p class="text-gray-700 text-xl leading-relaxed lg:border-l-4 lg:border-brand-500 lg:pl-6 italic">
                    "Menjadi brand jersey lokal yang merepresentasikan kultur sepak bola Indonesia secara autentik, serta mampu bersaing dan berdiri sejajar dengan jajaran brand internasional di kasta tertinggi."
                </p>
            </div>

            <!-- Mission -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-brand-900/5 border border-slate-100">
                <h2 class="text-2xl font-black text-brand-900 uppercase tracking-tight mb-8">Misi Kami</h2>
                <ul class="space-y-6">
                    <li class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-brand-900 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                        <div>
                            <h4 class="text-brand-900 font-black text-sm uppercase tracking-widest mb-1">Desain Orisinal</h4>
                            <p class="text-gray-600 font-medium text-sm italic">Menghadirkan jersey dengan desain autentik dan kualitas material premium yang memenuhi standar kebutuhan atlet profesional maupun pecinta olahraga.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-brand-900 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                        <div>
                            <h4 class="text-brand-900 font-black text-sm uppercase tracking-widest mb-1">Fusi Kultur</h4>
                            <p class="text-gray-600 font-medium text-sm italic">Mengeksplorasi batas antara sejarah sepak bola, kultur streetwear, dan gaya hidup modern guna menciptakan produk yang relevan untuk segala suasana.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-brand-900 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                        <div>
                            <h4 class="text-brand-900 font-black text-sm uppercase tracking-widest mb-1">Pemberdayaan</h4>
                            <p class="text-gray-600 font-medium text-sm italic">Memperkuat ekosistem sepak bola nasional melalui kolaborasi kreatif yang memberdayakan suporter dan komunitas pecinta sepak bola di seluruh penjuru negeri.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
