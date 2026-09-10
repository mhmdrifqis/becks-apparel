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
            <div x-data="{ 
                currentTab: 'semua', 
                searchQuery: '',
                showTrackingModal: false,
                trackingLoading: false,
                trackingData: null,
                async fetchTracking(orderId) {
                    this.showTrackingModal = true;
                    this.trackingLoading = true;
                    this.trackingData = null;
                    try {
                        let res = await fetch('/shipping/track/' + orderId);
                        let data = await res.json();
                        if (data.success) {
                            this.trackingData = data;
                        } else {
                            Swal.fire('Info', data.message || 'Gagal mengambil data resi.', 'info');
                        }
                    } catch(e) {
                        Swal.fire('Error', 'Gagal memuat status lacak pengiriman.', 'error');
                    } finally {
                        this.trackingLoading = false;
                    }
                }
            }">
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
                                    elseif ($key === 'dikemas') $count = $orders->whereIn('status', ['production', 'ready'])->count();
                                    elseif ($key === 'dikirim') $count = $orders->where('status', 'shipped')->count();
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
                    <input type="text" x-model="searchQuery" placeholder="Cari berdasarkan No. Pesanan atau Nama Produk..." class="bg-transparent border-none focus:ring-0 text-[11px] font-bold text-slate-600 w-full placeholder:text-slate-400">
                </div>

                <!-- Order List -->
                <div class="space-y-6">
                    @foreach($orders as $order)
                        @php
                            $category = 'semua';
                            if ($order->status === 'cancelled') $category = 'dibatalkan';
                            elseif ($order->status === 'completed') $category = 'selesai';
                            elseif ($order->payment_status === 'unpaid') $category = 'unpaid';
                            elseif (in_array($order->status, ['production', 'ready'])) $category = 'dikemas';
                            elseif ($order->status === 'shipped') $category = 'dikirim';

                            $statusText = strtoupper($order->status);
                            if ($order->payment_status === 'unpaid' && $order->status !== 'cancelled') $statusText = 'BELUM BAYAR';
                            
                            $productNames = addslashes(collect($order->orderItems)->map(function($i) { return $i->package->name; })->implode(' '));
                        @endphp

                        <div x-show="(currentTab === 'semua' || currentTab === '{{ $category }}') && ('{{ strtolower($order->order_number) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($productNames) }}'.includes(searchQuery.toLowerCase()))" x-transition class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative group">
                            
                            <!-- Card Header -->
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No. Pesanan: <span class="text-slate-900">{{ $order->order_number }}</span></span>
                                    </div>
                                    <div class="hidden md:flex items-center gap-2 border-l border-slate-200 pl-4">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[10px] font-bold text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $order->status === 'cancelled' ? 'text-red-500' : ($order->payment_status === 'unpaid' ? 'text-red-500' : 'text-brand-900') }}">
                                    {{ $statusText }}
                                </span>
                            </div>

                            <!-- Card Body (Items) -->
                            <div class="p-6 space-y-6">
                                @foreach($order->orderItems as $item)
                                    <div class="flex gap-4 md:gap-6 border-b border-slate-50 last:border-0 pb-6 last:pb-0">
                                        <div class="w-20 h-20 md:w-24 md:h-24 bg-slate-50 rounded-xl border border-slate-100 shrink-0 overflow-hidden">
                                            @if(count($item->package->images ?? []) > 0)
                                                @php $src = str_starts_with($item->package->images[0], 'assets/') ? asset($item->package->images[0]) : Storage::disk('public')->url($item->package->images[0]); @endphp
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
                                    @if($order->tracking_number)
                                        <button @click="fetchTracking({{ $order->id }})" class="px-6 py-3 bg-brand-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-800 transition-all shadow-md flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            Lacak Resi
                                        </button>
                                    @endif
                                    @if($order->payment_status === 'unpaid' && $order->status !== 'cancelled')
                                        <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-8 py-3 bg-brand-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/20 active:scale-95 text-center">Bayar Sekarang</a>
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

                <!-- Dynamic Webview Tracking Modal -->
                <div x-show="showTrackingModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak style="display: none;">
                    <div class="bg-white rounded-3xl w-full max-w-xl max-h-[85vh] overflow-hidden shadow-2xl flex flex-col" @click.away="showTrackingModal = false">
                        <!-- Modal Header -->
                        <div class="p-6 md:p-8 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-brand-900 to-slate-900 text-white">
                            <div>
                                <span class="px-2.5 py-1 bg-white/20 text-white text-[9px] font-black uppercase tracking-widest rounded-md backdrop-blur-md">Biteship Tracking Engine</span>
                                <h2 class="text-lg md:text-xl font-black uppercase tracking-tight mt-2 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Lacak Resi Real-Time
                                </h2>
                            </div>
                            <button @click="showTrackingModal = false" class="p-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Modal Content Body -->
                        <div class="flex-1 overflow-y-auto p-6 md:p-8">
                            <!-- Loading State -->
                            <template x-if="trackingLoading">
                                <div class="py-12 text-center">
                                    <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-brand-900 border-t-transparent mb-4"></div>
                                    <p class="text-xs font-black text-slate-700 uppercase tracking-widest">Menghubungkan ke API Biteship...</p>
                                    <p class="text-[10px] text-slate-400 font-bold mt-1">Mengambil histori perjalanan resi pengiriman Anda</p>
                                </div>
                            </template>

                            <!-- Data State -->
                            <template x-if="!trackingLoading && trackingData">
                                <div class="space-y-6">
                                    <!-- Summary Bar -->
                                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex flex-wrap items-center justify-between gap-4">
                                        <div>
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Ekspedisi / Kurir</p>
                                            <p class="text-sm font-black text-slate-900 uppercase" x-text="trackingData.courier"></p>
                                            <p class="text-xs font-bold text-brand-900 tracking-wider mt-0.5" x-text="'No. Resi: ' + trackingData.tracking_number"></p>
                                        </div>
                                        <div>
                                            <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border"
                                                  :class="{
                                                      'bg-green-50 text-green-700 border-green-200': trackingData.status === 'DELIVERED' || trackingData.status === 'SELESAI',
                                                      'bg-blue-50 text-blue-700 border-blue-200': trackingData.status === 'IN_TRANSIT' || trackingData.status === 'ON_DELIVERY',
                                                      'bg-amber-50 text-amber-700 border-amber-200': trackingData.status === 'PENDING' || trackingData.status === 'PICKUP'
                                                  }"
                                                  x-text="trackingData.status">
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Timeline -->
                                    <div>
                                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Histori Perjalanan Paket</h4>
                                        <ol class="relative border-s-2 border-slate-200 ms-3 space-y-6">
                                            <template x-for="(item, index) in trackingData.history" :key="index">
                                                <li class="ms-6">
                                                    <span class="absolute flex items-center justify-center w-6 h-6 rounded-full -start-3 ring-4 ring-white"
                                                          :class="index === 0 ? 'bg-brand-900 text-white' : 'bg-slate-200 text-slate-500'">
                                                        <template x-if="index === 0">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                        </template>
                                                        <template x-if="index !== 0">
                                                            <div class="w-1.5 h-1.5 bg-slate-400 rounded-full"></div>
                                                        </template>
                                                    </span>
                                                    <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3.5">
                                                        <div class="flex items-center justify-between gap-2 mb-1">
                                                            <time class="text-[9px] font-black text-brand-900 uppercase tracking-widest" x-text="item.date"></time>
                                                            <span x-show="item.location" class="text-[8px] font-bold text-slate-400 uppercase" x-text="item.location"></span>
                                                        </div>
                                                        <p class="text-xs font-bold text-slate-800 leading-snug" x-text="item.note"></p>
                                                    </div>
                                                </li>
                                            </template>
                                        </ol>
                                    </div>

                                    <!-- External Fallback Links -->
                                    <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-[10px]">
                                        <span class="text-slate-400 font-bold">Cek via tautan luar:</span>
                                        <div class="flex gap-2">
                                            <a :href="trackingData.parcelsapp_url" target="_blank" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-black uppercase tracking-wider rounded-lg transition-colors inline-flex items-center gap-1">
                                                ParcelsApp
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
