@extends('layouts.main')

@section('title', 'Becks Apparel - Home')

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
            <h1 class="text-6xl sm:text-8xl md:text-[12rem] lg:text-[16rem] font-black leading-tight tracking-[-0.05em] select-none animate-scale-up text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-400 to-green-400 py-4 px-10">
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
            <h2 class="text-4xl sm:text-7xl md:text-[15rem] font-black leading-none mb-12 tracking-tighter opacity-100 transform -translate-x-1 md:-translate-x-4">
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
            <h2 class="text-4xl sm:text-7xl md:text-[15rem] font-black leading-none mb-16 md:mb-20 tracking-tighter">
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
            <h2 class="text-4xl sm:text-7xl md:text-[12rem] font-black leading-none mb-8 md:mb-12 tracking-tighter text-right text-[#06402B]">
                KOLABORASI
            </h2>
            
            <div class="grid md:grid-cols-2 gap-12 md:gap-20 items-center">
                <div class="space-y-6 md:space-y-8">
                    <p class="text-xl md:text-2xl font-black uppercase tracking-tight mb-4 text-[#06402B]">Becks Apparel terbuka untuk berkolaborasi dengan:</p>
                    <ul class="space-y-3 md:space-y-4 text-base md:text-2xl font-medium text-[#06402B]/90">
                        @php
                            $collabs = [
                                'Komunitas suporter sepak bola',
                                'Klub sepak bola amatir maupun profesional',
                                'Sekolah sepak bola (SSB) atau akademi sepak bola',
                                'Pondok pesantren, sekolah negeri, maupun sekolah swasta',
                                'Perusahaan swasta maupun instansi pemerintah',
                                'Event olahraga',
                                'Brand sportwear maupun lifestyle'
                            ];
                        @endphp
                        @foreach($collabs as $c)
                            <li class="flex items-start gap-4">
                                <span class="w-2 md:w-3 h-2 md:h-3 bg-[#06402B] rounded-full mt-2.5 md:mt-3 flex-shrink-0"></span>
                                {{ $c }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="hidden md:block">
                     <div class="aspect-[4/5] bg-[#06402B] rounded-[3.5rem] p-1 text-white overflow-hidden shadow-2xl">
                          <img src="{{ asset('assets/images/portofolio/portofolio3.png') }}" class="w-full h-full object-cover rounded-[3.4rem] opacity-50 contrast-125">
                     </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: KENAPA BECKS? -->
    <section class="min-h-screen py-20 md:py-24 bg-[#fdfbf7]">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl sm:text-7xl md:text-[12rem] font-black leading-none mb-12 md:mb-16 tracking-tighter text-[#06402B]">
                KENAPA<br/>BECKS?
            </h2>
            
            <div class="grid md:grid-cols-12 gap-8 md:gap-12 text-[#06402B]">
                <div class="md:col-start-2 md:col-span-10">
                    <ul class="grid md:grid-cols-2 gap-x-12 md:gap-x-20 gap-y-8 md:gap-y-12">
                        @php
                            $reasons = [
                                ['title' => 'Harga Ramah', 'desc' => 'Harga ramah kantong karena custom jersey mulai dari Rp 90 ribu.'],
                                ['title' => 'Berpengalaman', 'desc' => 'Telah berdiri sejak 2018 dan berpengalaman memegang klub Liga 3 Indonesia.'],
                                ['title' => 'Mesin Sendiri', 'desc' => 'Mesin milik sendiri sehingga proses produksi terpadu dan terkontrol.'],
                                ['title' => 'Bahan Lengkap', 'desc' => 'Pilihan bahan superlengkap untuk berbagai kebutuhan gaya dan performa.'],
                                ['title' => 'Deadline Ketat', 'desc' => 'Berpengalaman menghadapi deadline ketat tanpa mengurangi kualitas hasil.'],
                                ['title' => 'Kapasitas Besar', 'desc' => 'Kapasitas produksi mencapai ribuan jersey per bulan.'],
                            ];
                        @endphp
                        @foreach($reasons as $r)
                            <li class="border-l-4 border-[#06402B] pl-8">
                                <h3 class="text-xl font-black uppercase tracking-widest mb-2">{{ $r['title'] }}</h3>
                                <p class="text-lg text-[#06402B]/70 font-medium leading-relaxed">{{ $r['desc'] }}</p>
                            </li>
                        @endforeach
                    </ul>
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
