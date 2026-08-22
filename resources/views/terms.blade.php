@extends('layouts.main')

@section('title', 'Terms of Service - Becks Apparel')

@section('content')
<div class="pt-32 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-slate-100">
            <h1 class="text-3xl md:text-4xl font-black text-brand-900 uppercase tracking-tighter mb-8">Terms of Service</h1>
            
            <div class="prose prose-slate max-w-none text-slate-600">
                <p class="font-medium mb-6">Terakhir diperbarui: {{ date('d M Y') }}</p>
                
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">1. Ketentuan Umum</h2>
                <p class="mb-4">Dengan mengakses dan menggunakan layanan Becks Apparel, Anda setuju untuk terikat dengan syarat dan ketentuan ini. Kami berhak mengubah syarat dan ketentuan sewaktu-waktu.</p>
                
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">2. Proses Pemesanan & Kustomisasi</h2>
                <p class="mb-4">Semua pesanan kustomisasi harus disetujui desain dan detailnya sebelum masuk proses produksi. Estimasi waktu produksi dapat bervariasi bergantung pada antrean dan kerumitan desain.</p>
                
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">3. Kebijakan Pembayaran</h2>
                <p class="mb-4">Pembayaran harus dilakukan sesuai dengan metode yang disediakan oleh sistem kami. Proses produksi akan dimulai setelah pembayaran terverifikasi.</p>

                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-4">4. Kebijakan Pengembalian (Refund & Retur)</h2>
                <p class="mb-4">Barang yang sudah diproduksi (kustomisasi) tidak dapat dikembalikan atau ditukar kecuali terdapat cacat produksi atau kesalahan dari pihak Becks Apparel.</p>
            </div>
        </div>
    </div>
</div>
@endsection
