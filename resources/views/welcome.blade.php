@extends('layouts.main')

@section('title', 'Home')

@section('content')
<div class="bg-[#fdfbf7] min-h-screen text-[#06402B] font-sans">
    
    <!-- SECTION 1: HERO (WEAR YOUR PRIDE) -->
    <section class="relative min-h-[90vh] md:h-screen flex flex-col items-center justify-center overflow-hidden py-20 md:py-0">
        <!-- Background Image Layer -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset('assets/images/hero_banner.png') }}" 
                alt="Becks Apparel Hero" 
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-[#06402B] via-[#06402B]/80 to-[#06402B]/60"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 mix-blend-overlay"></div>
        </div>

      

        <!-- Content -->
        <div class="relative z-10 text-center px-6">
            <h2 class="font-['Dancing_Script'] text-white text-3xl md:text-6xl mb-4 animate-fade-in-down drop-shadow-lg">
                Wear Your Pride
            </h2>
            <h1 class="text-6xl sm:text-5xl md:text-[12rem] lg:text-[16rem] font-black leading-tight tracking-[-0.05em] select-none animate-scale-up text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-400 to-green-400 py-4 px-10">
                BECKS
            </h1>
            
            <div class="mt-8 md:mt-12">
                <div class="inline-block px-6 py-3 md:px-12 md:py-4 bg-[#fdfbf7] text-[#06402B] rounded-full font-black text-[10px] md:text-base tracking-[0.1em] md:tracking-[0.2em] shadow-2xl animate-fade-in-up uppercase">
                    PT BOLA MEDIA SPORTAINMENT
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
            <div class="w-px h-12 md:h-16 bg-gradient-to-b from-transparent to-[#fdfbf7]/40"></div>
            <span class="text-[#fdfbf7]/40 text-[10px] uppercase font-bold tracking-[0.2em]">Scroll</span>
        </div>
    </section>

    <!-- SECTION 2: WELCOME -->
    <section class="min-h-screen py-20 md:py-24 flex items-center bg-[#fdfbf7]">
        <div class="max-w-7xl mx-auto px-6 overflow-hidden">
            <h2 class="text-4xl sm:text-7xl md:text-[10rem] font-black leading-none mb-12 tracking-tighter opacity-100 transform -translate-x-1 md:-translate-x-4">
                WELCOME
            </h2>
            <div class="grid md:grid-cols-12 gap-12">
                <div class="md:col-start-2 md:col-span-9">
                    <p class="text-xl md:text-4xl font-bold leading-[1.2] text-[#06402B]">
                        Becks Apparel adalah vendor jersey yang telah berkibar sejak <span class="bg-[#06402B] text-[#fdfbf7] px-2 italic">2018</span> dan bernaung di bawah PT Bola Media Sportainment. 
                    </p>
                    <p class="mt-8 text-lg md:text-2xl leading-relaxed text-[#06402B]/80 font-medium">
                        Sebagai sebuah brand, Becks Apparel telah dipercaya berbagai klub Liga 3 Indonesia. Mulai dari Jakarta United FC, PSJS Jakarta Selatan, Persikota Tangerang, ACN Muara Badak, hingga PS Belitung Timur. 
                    </p>
                    <p class="mt-8 text-lg md:text-2xl leading-relaxed text-[#06402B]/80 font-medium">
                        Dengan tagline <span class="font-black text-[#06402B]">#WearYourPride</span>, kamu tak perlu khawatir akan kualitas. Becks Apparel siap menciptakan jersey impianmu dengan hasil terbaik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: PRODUK -->
    <section class="min-h-screen py-20 md:py-24 bg-[#fdfbf7] relative">
        <div class="absolute top-12 right-12 hidden md:block">
            <span class="text-[#06402B] font-black text-2xl tracking-widest opacity-20">#WEARYOURPRIDE</span>
        </div>
        
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl sm:text-7xl md:text-[10rem] font-black leading-none mb-16 md:mb-20 tracking-tighter">
                PRODUK
            </h2>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
                @php
                    $products = [
                        ['name' => 'Jersey', 'img' => asset('assets/images/produk/jersey.png')],
                        ['name' => 'Jacket', 'img' => asset('assets/images/produk/jacket.png')],
                        ['name' => 'Tshirt', 'img' => asset('assets/images/produk/tshirt.png')],
                        ['name' => 'Kemeja', 'img' => asset('assets/images/produk/kemeja.png')],
                    ];
                @endphp
                
                @foreach($products as $prod)
                    <div class="group flex flex-col items-center text-center">
                        <div class="aspect-square w-full rounded-2xl md:rounded-[3rem] bg-white border border-slate-100 flex items-center justify-center p-4 md:p-8 transition-all duration-500 group-hover:-translate-y-2 md:group-hover:-translate-y-4 group-hover:shadow-2xl group-hover:border-[#06402B]/20">
                            <img src="{{ $prod['img'] }}" alt="{{ $prod['name'] }}" class="w-full h-full object-contain">
                        </div>
                        <h3 class="mt-4 md:mt-6 text-base md:text-2xl font-black uppercase tracking-widest">{{ $prod['name'] }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 4: KOLABORASI -->
    <section class="min-h-screen py-20 md:py-24 bg-[#fdfbf7] relative">
        <div class="absolute top-0 right-0 p-8">
            <img src="{{ asset('assets/images/logo-becks.png') }}" alt="Logo" class="h-8 md:h-12 w-auto opacity-10">
        </div>

        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl sm:text-7xl md:text-[10rem] font-black leading-none mb-8 md:mb-12 tracking-tighter text-right text-[#06402B]">
                KOLABORASI
            </h2>
            
            <div class="grid md:grid-cols-2 gap-12 md:gap-20 items-center">
                <div class="space-y-6 md:space-y-8">
                    <p class="text-xl md:text-2xl font-black uppercase tracking-tight mb-8 text-[#06402B]">Becks Apparel terbuka untuk berkolaborasi dengan:</p>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @php
                            $collabs = [
                                ['label' => 'Komunitas Suporter', 'icon' => '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/>'],
                                ['label' => 'Klub Sepak Bola', 'icon' => '<circle cx="12" cy="12" r="10"/><path d="m6.7 6.7 10.6 10.6"/><path d="m6.7 17.3 10.6-10.6"/>'],
                                ['label' => 'Sekolah & Akademi', 'icon' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>'],
                                ['label' => 'Pondok Pesantren', 'icon' => '<path d="m3 21 2-2h14l2 2"/><path d="M5 19v-4.5a1.5 1.5 0 0 1 1.5-1.5h11a1.5 1.5 0 0 1 1.5 1.5V19"/><path d="M8 13V7a4 4 0 1 1 8 0v6"/><path d="M12 3v2"/>'],
                                ['label' => 'Instansi & Swasta', 'icon' => '<rect width="16" height="20" x="4" y="2" rx="2"/><line x1="9" x2="15" y1="22" y2="22"/><line x1="12" x2="12" y1="18" y2="18"/><line x1="12" x2="12" y1="14" y2="14"/><line x1="12" x2="12" y1="10" y2="10"/>'],
                                ['label' => 'Event Olahraga', 'icon' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>'],
                                ['label' => 'Brand Sportswear', 'icon' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>'],
                            ];
                        @endphp
                        @foreach($collabs as $item)
                            <div class="flex items-center gap-4 p-4 rounded-3xl bg-white/50 border border-[#06402B]/5 hover:bg-white hover:shadow-xl hover:shadow-[#06402B]/5 transition-all group active:scale-95 duration-300">
                                <div class="w-12 h-12 rounded-2xl bg-[#06402B]/10 flex items-center justify-center text-[#06402B] group-hover:bg-[#06402B] group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $item['icon'] !!}
                                    </svg>
                                </div>
                                <span class="font-bold text-sm md:text-base leading-tight">{{ $item['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="hidden md:block group">
                     <div class="aspect-[4/5] bg-[#06402B] rounded-[3.5rem] p-1 text-white overflow-hidden shadow-2xl relative">
                          <img src="{{ asset('assets/images/portofolio/portofolio3.png') }}" 
                               class="w-full h-full object-cover rounded-[3.4rem] opacity-50 contrast-125 grayscale group-hover:grayscale-0 group-hover:opacity-100 scale-110 group-hover:scale-100 transition-all duration-700 ease-out">
                     </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: KENAPA BECKS? -->
    <section class="min-h-screen py-20 md:py-24 bg-[#fdfbf7]">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl sm:text-7xl md:text-[10rem] font-black leading-none mb-12 md:mb-16 tracking-tighter text-[#06402B]">
                KENAPA<br/>BECKS?
            </h2>
            
            <div class="grid md:grid-cols-12 gap-8 md:gap-12 text-[#06402B]">
                <div class="md:col-start-2 md:col-span-10">
                    <div class="grid md:grid-cols-2 gap-6 md:gap-10">
                        @php
                            $reasons = [
                                ['title' => 'Harga Ramah', 'desc' => 'Harga ramah kantong karena custom jersey mulai dari Rp 90 ribu.', 'icon' => '<path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/><path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4V12h-4Z"/>'],
                                ['title' => 'Berpengalaman', 'desc' => 'Telah berdiri sejak 2018 dan berpengalaman memegang klub Liga 3 Indonesia.', 'icon' => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>'],
                                ['title' => 'Mesin Sendiri', 'icon' => '<path d="M12 2v4"/><path d="m4.93 4.93 2.83 2.83"/><path d="M2 12h4"/><path d="m4.93 19.07 2.83-2.83"/><path d="M12 22v-4"/><path d="m19.07 19.07-2.83-2.83"/><path d="M22 12h-4"/><path d="m19.07 4.93-2.83 2.83"/><circle cx="12" cy="12" r="4"/>', 'desc' => 'Mesin milik sendiri sehingga proses produksi terpadu dan terkontrol.'],
                                ['title' => 'Bahan Lengkap', 'icon' => '<path d="m3 21 2-2h14l2 2"/><path d="M5 19V6.5a1.5 1.5 0 0 1 1.5-1.5h11a1.5 1.5 0 0 1 1.5 1.5V19"/><path d="M10 10h4"/>', 'desc' => 'Pilihan bahan superlengkap untuk berbagai kebutuhan gaya dan performa.'],
                                ['title' => 'Deadline Ketat', 'icon' => '<circle cx="12" cy="13" r="8"/><path d="M12 9v4l2 2"/><path d="m5 3 2 2"/><path d="m19 3-2 2"/>', 'desc' => 'Berpengalaman menghadapi deadline ketat tanpa mengurangi kualitas hasil.'],
                                ['title' => 'Kapasitas Besar', 'icon' => '<path d="M3 21h18"/><path d="M3 7v14"/><path d="M9 21V10l3-3 3 3v11"/><path d="M21 21V12l-3-3"/><path d="M12 12h.01"/><path d="M12 16h.01"/>', 'desc' => 'Kapasitas produksi mencapai ribuan jersey per bulan.'],
                            ];
                        @endphp
                        @foreach($reasons as $r)
                            <div class="group p-8 rounded-[2.5rem] bg-white border border-[#06402B]/5 shadow-sm hover:shadow-2xl hover:shadow-[#06402B]/5 transition-all duration-500 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1.5 h-0 bg-[#06402B] group-hover:h-full transition-all duration-500"></div>
                                <div class="w-14 h-14 rounded-2xl bg-[#06402B]/5 flex items-center justify-center text-[#06402B] mb-6 group-hover:scale-110 transition-transform duration-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $r['icon'] !!}
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black uppercase tracking-widest mb-3 transition-colors duration-300 group-hover:text-[#06402B]">{{ $r['title'] }}</h3>
                                <p class="text-lg text-[#06402B]/60 font-medium leading-relaxed">{{ $r['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="py-24 bg-[#06402B] text-[#fdfbf7] text-center">
        <h2 class="text-4xl md:text-7xl font-black mb-12 italic uppercase tracking-tighter">#WEARYOURPRIDE</h2>
        <a href="{{ route('customizer') }}" class="inline-flex px-12 py-6 bg-[#fdfbf7] text-[#06402B] rounded-full font-black text-xl hover:scale-110 active:scale-95 transition-all shadow-3xl uppercase tracking-tighter italic text-decoration-none">
            Mulai Kustomisasi Sekarang
        </a>
    </section>

</div>

<style>
    @keyframes fade-in-down {
        from { opacity: 0; transform: translateY(-40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scale-up {
        from { opacity: 0; scale: 0.8; }
        to { opacity: 1; scale: 1; }
    }
    .animate-fade-in-down { animation: fade-in-down 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in-up { animation: fade-in-up 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-scale-up { animation: scale-up 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    ::selection {
        background: #06402B;
        color: #fdfbf7;
    }
</style>
@endsection
