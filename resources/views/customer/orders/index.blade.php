@extends('layouts.main')

@section('title', 'Monitor Pesanan - Becks Apparel')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Header: Pure White & Minimal -->
    <div class="bg-white border-b border-slate-100 pt-28 pb-6 md:pt-36 md:pb-10">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Pesanan <span class="text-brand-600">Saya</span>
            </h1>
            <div class="hidden md:flex items-center gap-4">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Total {{ count($orders) }} Pesanan</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12 md:py-16">

        @if($orders->isEmpty())
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-12 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Belum ada pesanan</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2 mb-8">Mulailah membuat jersey kustom pertama Anda!</p>
                <a href="{{ route('catalog.index') }}" class="inline-flex px-8 py-4 bg-brand-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-brand-900/20 active:scale-95 transition-all">Lihat Katalog</a>
            </div>
        @else
            <div x-data="{ currentTab: 'semua' }">
                <!-- Shopee Style Status Tabs -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-8 overflow-hidden">
                    <div class="flex overflow-x-auto no-scrollbar">
                        @php
                            $tabs = [
                                'semua' => 'Semua',
                                'unpaid' => 'Belum Bayar',
                                'dikemas' => 'Dikemas',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan'
                            ];
                        @endphp
                        @foreach($tabs as $key => $label)
                            <button @click="currentTab = '{{ $key }}'" 
                                    class="flex-1 min-w-[100px] py-4 text-[10px] font-black uppercase tracking-widest transition-all border-b-2 relative"
                                    :class="currentTab === '{{ $key }}' ? 'text-brand-900 border-brand-900 bg-brand-50/30' : 'text-slate-400 border-transparent hover:text-slate-600'">
                                {{ $label }}
                                @php
                                    $count = 0;
                                    if ($key === 'semua') $count = $orders->count();
                                    elseif ($key === 'unpaid') $count = $orders->where('payment_status', 'unpaid')->where('status', '!=', 'cancelled')->count();
                                    elseif ($key === 'dikemas') $count = $orders->where('payment_status', '!=', 'unpaid')->whereIn('status', ['pending', 'printing', 'sewing', 'qc'])->count();
                                    elseif ($key === 'dikirim') $count = $orders->whereIn('status', ['ready', 'shipped'])->count();
                                    elseif ($key === 'selesai') $count = $orders->where('status', 'completed')->count();
                                    elseif ($key === 'dibatalkan') $count = $orders->where('status', 'cancelled')->count();
                                @endphp
                                @if($count > 0)
                                    <span class="ml-1 text-[8px] font-bold" :class="currentTab === '{{ $key }}' ? 'text-brand-900' : 'text-slate-300'">({{ $count }})</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Search Placeholder (Shopee Style) -->
                <div class="bg-slate-200/50 rounded-xl px-4 py-3 mb-8 flex items-center gap-3">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Cari berdasarkan No. Pesanan atau Nama Produk..." class="bg-transparent border-none focus:ring-0 text-[11px] font-bold text-slate-600 w-full placeholder:text-slate-400">
                </div>

                <!-- Order List -->
                <div class="space-y-6">
                    @foreach($orders as $order)
                        @php
                            $category = 'semua';
                            if ($order->status === 'cancelled') $category = 'dibatalkan';
                            elseif ($order->payment_status === 'unpaid') $category = 'unpaid';
                            elseif (in_array($order->status, ['pending', 'printing', 'sewing', 'qc'])) $category = 'dikemas';
                            elseif (in_array($order->status, ['ready', 'shipped'])) $category = 'dikirim';
                            elseif ($order->status === 'completed') $category = 'selesai';

                            $statusText = strtoupper($order->status);
                            if ($order->payment_status === 'unpaid' && $order->status !== 'cancelled') $statusText = 'BELUM BAYAR';
                        @endphp

                        <div x-show="currentTab === 'semua' || currentTab === '{{ $category }}'" x-transition 
                             class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative group">
                            
                            <!-- Card Header -->
                            <div class="px-6 py-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">No. Pesanan:</span>
                                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">{{ $order->order_number }}</span>
                                </div>
                                <div class="text-[10px] font-black tracking-widest uppercase {{ $category === 'unpaid' ? 'text-red-500' : ($category === 'dibatalkan' ? 'text-slate-400' : 'text-brand-600') }}">
                                    {{ $statusText }}
                                </div>
                            </div>

                            <!-- Card Body (Items) -->
                            <div class="p-6 space-y-6">
                                @foreach($order->orderItems as $item)
                                    <div class="flex gap-4 md:gap-6 border-b border-slate-50 last:border-0 pb-6 last:pb-0">
                                        <div class="w-20 h-20 md:w-24 md:h-24 bg-slate-50 rounded-xl border border-slate-100 shrink-0 overflow-hidden">
                                            @if(count($item->package->images ?? []) > 0)
                                                @php $src = str_starts_with($item->package->images[0], 'assets/') ? asset($item->package->images[0]) : Storage::url($item->package->images[0]); @endphp
                                                <img src="{{ $src }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm md:text-lg font-black text-slate-900 uppercase tracking-tight truncate">{{ $item->package->name }}</h4>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Bahan: {{ $item->material->name }}</p>
                                            <p class="text-[10px] font-bold text-slate-900 mt-1 uppercase">x{{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right flex flex-col justify-end">
                                            <p class="text-sm md:text-base font-black text-brand-900">Rp {{ number_format($item->subtotal / $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Card Footer -->
                            <div class="px-6 py-6 border-t border-slate-50 bg-slate-50/10">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                    <div class="flex items-center gap-2 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">Informasi status tersedia di rincian</span>
                                    </div>
                                    <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">
                                        <div class="text-right">
                                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Pesanan:</p>
                                            <p class="text-2xl font-black text-brand-900 tracking-tighter leading-none">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 flex flex-wrap justify-end gap-3">
                                    @if($category === 'unpaid' && $order->status !== 'cancelled')
                                        <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="type" value="dp">
                                            <button type="submit" class="px-8 py-3 bg-brand-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/20 active:scale-95">Bayar Sekarang</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-8 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Detail Pesanan</a>
                                    @if($order->status === 'completed')
                                        <button class="px-8 py-3 bg-brand-50 text-brand-900 border border-brand-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-100 transition-all">Beli Lagi</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
