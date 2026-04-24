@extends('layouts.main')

@section('title', 'Portfolio')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- Hero Section: Full height & immersive like Catalog -->
    <section class="relative h-[80vh] flex items-center overflow-hidden bg-brand-950">
        <!-- Background Layer -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset('assets/images/portofolio/portofolio3.png') }}" 
                alt="Portfolio Hero" 
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-[#06402B] via-[#06402B]/80 to-[#06402B]/60"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 mix-blend-overlay"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20">
            <div class="max-w-3xl">
                <span class="inline-block px-4 py-1.5 rounded-full bg-brand-900/40 border border-brand-500/20 text-brand-400 font-bold text-xs tracking-[0.2em] uppercase mb-8 backdrop-blur-md">
                    Our Work
                </span>
                <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter leading-none mb-8">
                    MASTER<br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-green-300">PIECE PIECES.</span>
                </h1>
                <p class="text-brand-100/60 text-lg md:text-xl mb-12 max-w-xl leading-relaxed">
                    Koleksi karya terbaik yang telah kami wujudkan untuk para juara di lapangan.
                </p>
            </div>
        </div>

        <!-- Bottom Scroller Info -->
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 hidden md:block">
            <div class="flex items-center gap-3 text-brand-400/40 text-[10px] uppercase font-bold tracking-[0.3em]">
                <span class="w-8 h-px bg-current"></span>
                Scroll to Explore
                <span class="w-8 h-px bg-current"></span>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-20">
        @php
            $portfolios = [
                ['img' => 'portofolio1.png', 'title' => 'Jakarta United', 'desc' => 'Menjadi official apparel partner untuk klub Liga 3 DKI Jakarta musim 2019: Jakarta United'],
                ['img' => 'portofolio2.png', 'title' => 'PSJS Jakarta Selatan', 'desc' => 'Menjadi official apparel partner untuk klub Liga 3 DKI Jakarta musim 2019: PSJS Jakarta Selatan'],
                ['img' => 'portofolio3.png', 'title' => 'Persikota Tangerang', 'desc' => 'Menjadi official sponsor apparel untuk klub Liga 3 Banten musim 2019: Persikota Tangerang'],
                ['img' => 'portofolio4.png', 'title' => 'ACN Muara Badak', 'desc' => 'Menjadi official apparel partner untuk klub Liga 3 Kalimantan Timur musim 2019: ACN Muara Badak'],
                ['img' => 'portofolio5.png', 'title' => 'PS Belitung Timur', 'desc' => 'Menjadi official apparel partner untuk klub Liga 3 Bangka Belitung musim 2021: PS Belitung Timur'],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($portfolios as $index => $item)
                <div class="group relative bg-white rounded-[2.5rem] overflow-hidden shadow-2xl shadow-brand-950/10 hover:shadow-brand-950/20 transition-all duration-500 border border-slate-100">
                    <!-- Image Container -->
                    <div class="aspect-[4/5] overflow-hidden bg-slate-100">
                        <img src="{{ asset('assets/images/portofolio/' . $item['img']) }}" 
                             alt="Becks Apparel Portfolio {{ $item['title'] }}" 
                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 group-hover:rotate-2">
                    </div>
                    
                    <!-- Overlay Info -->
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-950 via-brand-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-10 text-white">
                        <span class="text-brand-400 font-bold text-xs uppercase tracking-[0.3em] mb-3 font-sans italic">Becks Project #00{{ $index + 1 }}</span>
                        <h3 class="text-2xl font-black uppercase tracking-tight mb-3 leading-none">{{ $item['title'] }}</h3>
                        <p class="text-brand-50/70 text-sm italic font-medium leading-relaxed mb-6">{{ $item['desc'] }}</p>
                        
                        <div class="flex gap-2">
                             <div class="w-10 h-1 bg-brand-500 rounded-full"></div>
                             <div class="w-4 h-1 bg-brand-500/40 rounded-full"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- CTA Section -->
    <div class="max-w-7xl mx-auto px-4 pb-24">
        <div class="bg-brand-900 rounded-[3.5rem] p-12 md:p-24 relative overflow-hidden text-center text-white shadow-3xl">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
            
            <!-- Radial Accents -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-green-500/10 rounded-full blur-[120px]"></div>

            <div class="relative z-10">
                <h2 class="text-3xl md:text-6xl font-black uppercase tracking-tighter mb-8 italic">Siap Mewujudkan <br/><span class="text-brand-400">Desain Tim Anda?</span></h2>
                <p class="text-brand-100/70 max-w-xl mx-auto mb-12 text-lg md:text-xl font-medium">Bawa identitas tim Anda ke level berikutnya dengan kualitas apparel kelas dunia.</p>
                <a href="{{ route('customizer') }}" class="inline-flex px-12 py-6 bg-white text-brand-900 rounded-full font-black text-lg hover:bg-brand-50 hover:scale-110 active:scale-95 transition-all shadow-3xl uppercase tracking-widest italic text-decoration-none">
                    Buka Online Customizer
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
