<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Becks Apparel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-becks.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Syne:wght@400..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-zinc-100 antialiased selection:bg-brand-500 selection:text-brand-950">
        <div class="relative min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 overflow-hidden bg-brand-950">
            <!-- Background Image & Texture Overlay -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <div class="absolute inset-0 bg-gradient-to-br from-[#04251a] via-[#06402B] to-[#021811]"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-30 mix-blend-overlay"></div>
                
                <!-- Radial Glow Orbs -->
                <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-10 right-10 w-80 h-80 bg-brand-800/20 rounded-full blur-3xl pointer-events-none"></div>
            </div>

            <!-- Logo Header -->
            <div class="relative z-10 mb-6 text-center">
                <a href="/" class="inline-flex flex-col items-center group transition-transform duration-300 hover:scale-105">
                    <x-application-logo class="w-16 h-16 fill-current text-brand-400 drop-shadow-[0_0_15px_rgba(212,175,55,0.4)]" />
                    <span class="mt-2 font-display text-2xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-400 to-yellow-300">
                        BECKS APPAREL
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-brand-300/60">
                        Wear Your Pride
                    </span>
                </a>
            </div>

            <!-- Glassmorphic Auth Card -->
            <div class="relative z-10 w-full sm:max-w-md bg-zinc-900/90 backdrop-blur-2xl border border-white/10 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] overflow-hidden rounded-[2.5rem] p-6 sm:p-8">
                {{ $slot }}
            </div>

            <!-- Footer Copy -->
            <div class="relative z-10 mt-8 text-center text-xs text-brand-300/50 font-medium">
                &copy; {{ date('Y') }} PT Bola Media Sportainment. All rights reserved.
            </div>
        </div>
    </body>
</html>
