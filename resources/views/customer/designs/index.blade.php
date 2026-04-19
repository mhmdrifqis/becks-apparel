@extends('layouts.main')

@section('title', 'Koleksi Desain Saya - Becks Apparel')

@section('content')
    <div class="min-h-screen bg-[#fdfbf7] py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
            <!-- Header -->
            <div class="mb-12 md:mb-20">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="flex-1">
                        <h1 class="text-4xl md:text-7xl font-black text-brand-900 tracking-tighter uppercase mb-4">
                            Desain <span class="text-brand-600">Saya</span>
                        </h1>
                        <p class="text-lg text-gray-600 max-w-2xl">
                            Kelola koleksi rancangan jersey kustom Anda. Anda dapat melihat kembali, mengedit, atau menghapus desain yang telah Anda simpan.
                        </p>
                    </div>
                </div>
            </div>

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
