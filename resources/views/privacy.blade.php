@extends('layouts.main')

@section('title', 'Privacy Policy - Becks Apparel')

@section('content')
<div class="pt-32 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-slate-100">
            <h1 class="text-3xl md:text-4xl font-black text-brand-900 uppercase tracking-tighter mb-8">Privacy Policy</h1>
            
            <div class="prose prose-slate max-w-none text-slate-600">
                <p class="font-medium mb-6">Terakhir diperbarui: {{ date('d M Y') }}</p>
                
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">1. Informasi yang Kami Kumpulkan</h2>
                <p class="mb-4">Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami saat mendaftar, memesan, atau berkomunikasi dengan layanan pelanggan kami. Informasi ini dapat berupa nama, alamat email, nomor telepon, dan alamat pengiriman.</p>
                
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">2. Penggunaan Informasi</h2>
                <p class="mb-4">Informasi yang kami kumpulkan digunakan untuk memproses pesanan, mengelola akun Anda, dan memberikan layanan terbaik terkait kustomisasi jersey di Becks Apparel.</p>
                
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">3. Keamanan Data</h2>
                <p class="mb-4">Kami berkomitmen untuk menjaga keamanan data pribadi Anda dan tidak akan membagikannya kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.</p>

                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">4. Perubahan Kebijakan</h2>
                <p class="mb-4">Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Setiap perubahan akan diberitahukan melalui halaman ini.</p>
            </div>
        </div>
    </div>
</div>
@endsection
