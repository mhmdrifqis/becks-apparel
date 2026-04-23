@extends('layouts.main')

@section('title', 'Koleksi Desain Saya - Becks Apparel')

@section('content')
    <div class="min-h-screen bg-slate-50">
        <!-- Header: Pure White & Minimal -->
        <div class="bg-white border-b border-slate-100 pt-28 pb-6 md:pt-36 md:pb-10">
            <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
                <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                    <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Desain <span class="text-brand-600">Saya</span>
                </h1>
                <div class="hidden md:flex items-center gap-4">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Total {{ count($designs ?? []) }} Desain</span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">

            <!-- Content -->
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden min-h-[400px]">
                @if(isset($designs) && count($designs) > 0)
                    <!-- UI for when designs exist (Placeholder for now) -->
                    <div class="p-8 md:p-12">
                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                             <!-- Design cards will go here -->
                         </div>
                    </div>
                @else
                    <!-- Empty State (Matches Orders Theme) -->
                    <div class="p-8 md:p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
                        <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-full bg-brand-50 text-brand-900">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Desain</h2>
                        <p class="text-gray-500 mb-8 max-w-sm mx-auto">
                            Anda belum memiliki koleksi desain yang disimpan. Buat desain impian Anda di Customizer sekarang!
                        </p>
                        <a href="{{ route('customizer') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand-900 text-white rounded-full font-black uppercase tracking-widest text-sm hover:bg-brand-800 transition-all shadow-lg active:scale-95">
                            Mulai Desain Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
