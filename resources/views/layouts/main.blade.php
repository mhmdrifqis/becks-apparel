<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Becks Apparel') }} - @yield('title', 'Premium Sports Apparel')</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-becks.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=Inter:wght@400;500;600;700;800;900&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

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
            showAuthModal: @if(session('show_auth_modal') || $errors->any()) true @else false @endif, 
            authMode: @if($errors->has('name') || $errors->has('phone') || $errors->has('password_confirmation')) 'register' @else 'login' @endif 
        }" 
        @open-auth-modal.window="showAuthModal = true; authMode = $event.detail?.mode || 'login'"
        class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-zinc-950 dark:text-zinc-100 transition-colors duration-500 overflow-x-hidden"
    >
        <div class="min-h-screen overflow-x-hidden w-full max-w-[100vw]">
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

        <!-- Auth Modal (Global) -->
        <x-login-modal />

        <!-- Toast Notifications (Global) -->
        <x-toast />

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
            document.addEventListener('DOMContentLoaded', function() {
                const preloader = document.getElementById('preloader');
                if (preloader) {
                    // Beri jeda sangat sebentar agar UI kerender, lalu hilangkan
                    setTimeout(() => {
                        preloader.classList.add('opacity-0');
                        setTimeout(() => {
                            preloader.style.display = 'none';
                        }, 500);
                    }, 150);
                }

                // Flash Messages to Toast
                @if(session('success'))
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: "{{ session('success') }}", type: 'success' } }));
                @endif
                @if(session('error'))
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: "{{ session('error') }}", type: 'error' } }));
                @endif
                @if(session('info'))
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: "{{ session('info') }}", type: 'warning' } }));
                @endif
            });

            // Fallback: Pastikan loading screen hilang paksa setelah 3 detik apapun yang terjadi
            setTimeout(() => {
                const p = document.getElementById('preloader');
                if (p && p.style.display !== 'none') {
                    p.classList.add('opacity-0');
                    setTimeout(() => p.style.display = 'none', 500);
                }
            }, 3000);
        </script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmAction(event, message) {
                event.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#18181b', // brand-900
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    background: document.documentElement.classList.contains('dark') ? '#09090b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#18181b',
                    customClass: {
                        popup: 'rounded-2xl border border-gray-200 dark:border-zinc-800',
                        confirmButton: 'rounded-full px-6 py-2 text-sm font-bold',
                        cancelButton: 'rounded-full px-6 py-2 text-sm font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.closest('form').submit();
                    }
                });
            }
        </script>
        <!-- Chatbot Widget -->
        <x-chatbot-widget />

        @stack('scripts')
    </body>
</html>
