<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Becks Apparel') }} - @yield('title', 'Premium Sports Apparel')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body 
        x-data="{ 
            showAuthModal: @if($errors->any()) true @else false @endif, 
            authMode: 'login' 
        }" 
        class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-zinc-950 dark:text-zinc-100 transition-colors duration-500"
    >
        <div class="min-h-screen">
            <!-- Preloader -->
            <x-preloader />

            <!-- Navigation -->
            @unless(isset($hideNavFooter) && $hideNavFooter)
                <x-navbar />
            @endunless

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            @unless(isset($hideNavFooter) && $hideNavFooter)
                <x-footer />
            @endunless
        </div>

        <!-- Theme Toggle Logic -->
        <script>
            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }

            // Preloader Logic
            window.onload = function() {
                const preloader = document.getElementById('preloader');
                if (preloader) {
                    preloader.classList.add('opacity-0');
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 700);
                }
            };
        </script>
    </body>
</html>
