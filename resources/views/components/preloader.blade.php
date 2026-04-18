<div 
    id="preloader" 
    class="fixed inset-0 z-[200] flex items-center justify-center bg-zinc-950 transition-opacity duration-700"
>
    <div class="relative">
        <!-- Pulse effect back -->
        <div class="absolute inset-0 bg-brand-900/30 rounded-full blur-2xl animate-pulse scale-150"></div>
        
        <!-- Logo container -->
        <div class="relative animate-bounce-slow">
            <x-application-logo class="h-24 w-auto drop-shadow-[0_0_15px_rgba(6,64,43,0.5)]" />
        </div>
        
        <!-- Loading bar -->
        <div class="mt-12 w-48 h-1 bg-zinc-900 rounded-full overflow-hidden relative">
            <div class="absolute inset-y-0 left-0 bg-brand-500 w-full animate-loader-progress"></div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 3s ease-in-out infinite;
    }
    @keyframes loader-progress {
        0% { transform: translateX(-100%); }
        50% { transform: translateX(0); }
        100% { transform: translateX(100%); }
    }
    .animate-loader-progress {
        animation: loader-progress 2s ease-in-out infinite;
    }
</style>
