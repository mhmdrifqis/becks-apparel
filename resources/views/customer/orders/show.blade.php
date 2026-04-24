@extends('layouts.main')

@section('title', 'Detail Pesanan ' . $order->order_number . ' - Becks Apparel')

@section('content')
<div class="min-h-screen bg-slate-50 pb-40">
    <div class="bg-white border-b border-slate-100 pt-28 pb-6 md:pt-36 md:pb-10">
        <div class="max-w-7xl mx-auto px-4">
            <a href="{{ route('customer.orders') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-brand-900 transition-colors mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Pesanan
            </a>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8">
                <div>
                    <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                        <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Rincian <span class="text-brand-600">Pesanan</span>
                    </h1>
                    <p class="text-xs font-bold text-slate-300 uppercase tracking-widest mt-2">#{{ $order->order_number }}</p>
                    <p class="mt-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Dibuat pada {{ $order->created_at->format('d F Y, H:i') }}
                    </p>
                </div>
                
                <div class="flex flex-col items-end gap-3">
                    <div class="flex items-center gap-2">
                        @if($order->payment_status !== 'paid' && $order->midtrans_order_id)
                            <form action="{{ route('payment.sync', $order->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-xl border-2 border-brand-900 bg-white text-brand-900 text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-brand-50 transition-all flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Cek Status
                                </button>
                            </form>
                        @endif
                        <div class="px-4 py-2 rounded-xl border-2 font-black text-[10px] uppercase tracking-widest
                            {{ $order->payment_status === 'paid' ? 'border-green-100 bg-green-50 text-green-700' : 
                               ($order->payment_status === 'partial' ? 'border-amber-100 bg-amber-50 text-amber-700' : 'border-red-100 bg-red-50 text-red-600') }}">
                            {{ $order->payment_status === 'paid' ? 'LUNAS' : ($order->payment_status === 'partial' ? 'DP DIBAYAR' : 'BELUM DIBAYAR') }}
                        </div>
                        <div class="px-4 py-2 rounded-xl border-2 border-slate-100 bg-white text-slate-600 text-[10px] font-black uppercase tracking-widest shadow-sm">
                            {{ strtoupper($order->status) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-8 p-6 md:p-12 overflow-hidden relative">
            <div class="overflow-x-auto no-scrollbar -mx-6 px-6">
                <div class="flex items-start justify-between min-w-[600px] md:min-w-0 relative py-4">
                    <div class="absolute top-[48px] left-[10%] right-[10%] h-0.5 bg-slate-100 z-0"></div>
                    
                    @php
                        $isCancelled = $order->status === 'cancelled';
                        
                        if ($isCancelled) {
                            $trackingStages = [
                                ['key' => 'created', 'label' => 'Dibuat', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                ['key' => 'cancelled', 'label' => 'Dibatalkan', 'icon' => 'M6 18L18 6M6 6l12 12']
                            ];
                            $currentStageKey = 'cancelled';
                        } else {
                            $trackingStages = [
                                ['key' => 'created', 'label' => 'Dibuat', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                ['key' => 'paid', 'label' => 'Dibayar', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                                ['key' => 'production', 'label' => 'Produksi', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                ['key' => 'shipped', 'label' => 'Dikirim', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                ['key' => 'completed', 'label' => 'Selesai', 'icon' => 'M5 13l4 4L19 7']
                            ];
                            
                            $currentStageKey = 'created';
                            if ($order->status === 'completed') $currentStageKey = 'completed';
                            elseif (in_array($order->status, ['ready', 'shipped'])) $currentStageKey = 'shipped';
                            elseif (in_array($order->status, ['printing', 'sewing', 'qc'])) $currentStageKey = 'production';
                            elseif ($order->payment_status !== 'unpaid') $currentStageKey = 'paid';
                        }

                        $foundCurrent = false;
                    @endphp

                    @foreach($trackingStages as $idx => $stage)
                        @php
                            $isCompleted = false;
                            $isCurrent = false;
                            
                            if (!$foundCurrent) {
                                if ($currentStageKey === $stage['key']) {
                                    $isCurrent = true;
                                    $foundCurrent = true;
                                } else {
                                    $isCompleted = true;
                                }
                            }
                            
                            $isStageCancelled = $stage['key'] === 'cancelled';
                        @endphp
                        <div class="flex flex-col items-center text-center w-32 relative z-10">
                            @if($idx > 0 && ($isCompleted || $isCurrent))
                                <div class="absolute top-[32px] -left-1/2 w-full h-0.5 {{ $isStageCancelled ? 'bg-red-500' : 'bg-brand-900' }} -z-10"></div>
                            @endif

                            <div class="w-12 h-12 md:w-16 md:h-16 rounded-full border-4 flex items-center justify-center transition-all duration-700
                                {{ $isCompleted ? 'bg-brand-900 border-white text-white shadow-xl shadow-brand-900/20' : 
                                   ($isCurrent ? ($isStageCancelled ? 'bg-red-500 border-white text-white shadow-xl shadow-red-500/20 scale-110' : 'bg-white border-brand-900 text-brand-900 shadow-xl shadow-brand-900/10 scale-110') : 'bg-white border-slate-100 text-slate-200') }}">
                                <svg class="w-5 h-5 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $stage['icon'] }}"></path>
                                </svg>
                            </div>
                            <div class="mt-4 px-2">
                                <p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest {{ $isCompleted || $isCurrent ? ($isStageCancelled ? 'text-red-500' : 'text-slate-900') : 'text-slate-300' }}">{{ $stage['label'] }}</p>
                                @if($isCurrent)
                                    <p class="text-[7px] md:text-[8px] font-bold {{ $isStageCancelled ? 'text-red-400' : 'text-brand-600' }} uppercase mt-1">
                                        {{ $isStageCancelled ? 'Terhenti' : 'Berlangsung' }}
                                    </p>
                                @elseif($isCompleted)
                                    <p class="text-[7px] md:text-[8px] font-bold text-slate-400 uppercase mt-1">{{ $order->updated_at->format('d/m') }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                    <div class="h-1 w-full bg-[repeating-linear-gradient(45deg,#064e3b,#064e3b_10px,#fff_10px,#fff_20px,#ca8a04_20px,#ca8a04_30px,#fff_30px,#fff_40px)]"></div>
                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <svg class="w-5 h-5 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <h2 class="text-xs font-black text-brand-900 uppercase tracking-widest">Informasi Penerima & Alamat</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Penerima</p>
                                <p class="text-sm font-black text-slate-900 uppercase">{{ $order->recipient_name }}</p>
                                <p class="text-xs font-bold text-slate-500 mt-1">{{ $order->recipient_phone }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Lokasi Pengiriman</p>
                                <p class="text-xs font-bold text-slate-600 leading-relaxed italic">"{{ $order->shipping_address }}"</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Produksi Berpindah Ke Sini -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Timeline Produksi</h2>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Histori Pengerjaan</span>
                    </div>
                    <div class="p-6 md:p-8">
                        @if($order->statusLogs->count() > 0)
                            <ol class="relative border-s border-slate-200 ms-3 space-y-8">                  
                                @foreach($order->statusLogs->sortByDesc('created_at') as $index => $log)
                                    <li class="ms-6">            
                                        <span class="absolute flex items-center justify-center w-6 h-6 {{ $index === 0 ? 'bg-brand-900 ring-brand-100' : 'bg-slate-200 ring-white' }} rounded-full -start-3 ring-4">
                                            @if($index === 0)
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full"></div>
                                            @endif
                                        </span>
                                        <div class="flex flex-col gap-1">
                                            <time class="inline-block w-fit bg-slate-100 border border-slate-200 text-slate-500 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full mb-1">
                                                {{ $log->created_at->format('d M Y, H:i') }} WIB
                                            </time>
                                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">
                                                {{ $log->description }}
                                                @if($index === 0)
                                                    <span class="ms-2 bg-brand-50 border border-brand-100 text-brand-900 text-[8px] font-black px-1.5 py-0.5 rounded uppercase tracking-tighter">Terbaru</span>
                                                @endif
                                            </h3>
                                            <p class="text-[11px] font-medium text-slate-400 leading-relaxed italic">
                                                Tahapan produksi ini tercatat secara otomatis oleh sistem Becks Apparel.
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <div class="text-center py-4">
                                <p class="text-xs font-bold text-slate-400 italic">Pesanan Anda akan masuk ke antrean produksi setelah pembayaran dikonfirmasi.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 mt-2">Daftar Produk</h2>
                    @foreach($order->orderItems as $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-data="{ openRoster: false }">
                            <div class="p-6 md:p-8">
                                <div class="flex gap-4 md:gap-6">
                                    <div class="w-20 h-20 md:w-24 md:h-24 bg-slate-50 rounded-2xl border border-slate-100 shrink-0 overflow-hidden group relative">
                                        @if(count($item->package->images ?? []) > 0)
                                            @php $src = str_starts_with($item->package->images[0], 'assets/') ? asset($item->package->images[0]) : Storage::url($item->package->images[0]); @endphp
                                            <img src="{{ $src }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2 py-0.5 bg-brand-100 text-brand-900 text-[8px] font-black uppercase tracking-widest rounded-md">{{ $item->package->category }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Qty: {{ $item->quantity }} Pcs</span>
                                        </div>
                                        <h3 class="text-sm md:text-lg font-black text-slate-900 uppercase tracking-tight truncate mb-2">{{ $item->package->name }}</h3>
                                        <div class="flex flex-wrap gap-4">
                                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Bahan: <span class="text-brand-900 font-black">{{ $item->material->name }}</span></div>
                                        </div>
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <button @click="openRoster = true" class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-black uppercase tracking-widest text-slate-600 hover:border-brand-900 transition-all">Lihat Roster Data</button>
                                        </div>
                                    </div>
                                    <div class="text-right hidden md:block">
                                        <p class="text-xl font-black text-brand-900 tracking-tighter">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="mt-8 pt-8 border-t border-slate-50">
                                     <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Lampiran Desain / Referensi</h4>
                                     <div class="flex flex-wrap gap-4">
                                         @if($item->design)
                                             @php 
                                                 $design = $item->design;
                                                 $designJson = $design->design_json;
                                                 $files = is_array($designJson) && isset($designJson['files']) ? $designJson['files'] : (is_array($designJson) && isset($designJson['file']) ? [$designJson['file']] : []);
                                             @endphp

                                             @if($design->preview_path)
                                                 <a href="{{ asset('storage/' . $design->preview_path) }}" target="_blank" class="w-24 h-24 bg-slate-50 rounded-xl border-2 border-brand-100 overflow-hidden hover:border-brand-900 transition-all group relative">
                                                     <img src="{{ asset('storage/' . $design->preview_path) }}" class="w-full h-full object-contain">
                                                     <div class="absolute inset-0 bg-brand-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                                         <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                     </div>
                                                 </a>
                                             @endif

                                             @foreach($files as $file)
                                                 <a href="{{ Storage::url($file) }}" target="_blank" class="w-24 h-24 bg-slate-50 rounded-xl border-2 border-brand-100 overflow-hidden hover:border-brand-900 transition-all group relative">
                                                     <img src="{{ Storage::url($file) }}" class="w-full h-full object-cover">
                                                     <div class="absolute inset-0 bg-brand-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                                         <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                     </div>
                                                 </a>
                                             @endforeach

                                             @if(!$design->preview_path && count($files) === 0)
                                                 <p class="text-[10px] font-bold text-slate-400 italic">Tidak ada file lampiran.</p>
                                             @endif
                                         @else
                                             <p class="text-[10px] font-bold text-slate-400 italic">Desain belum ditentukan.</p>
                                         @endif
                                     </div>
                                </div>
                            </div>

                            <div x-show="openRoster" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                                <div class="bg-white rounded-3xl w-full max-w-5xl max-h-[85vh] overflow-hidden shadow-2xl flex flex-col" @click.away="openRoster = false">
                                    <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                        <div>
                                            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Detail Roster Tim</h2>
                                            <p class="text-xs font-bold text-slate-500 uppercase mt-1">{{ $item->package->name }} - {{ $item->quantity }} Pcs</p>
                                        </div>
                                        <button @click="openRoster = false" class="p-3 bg-white rounded-xl border border-slate-200 text-slate-400 hover:text-red-500 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="flex-1 overflow-y-auto p-8">
                                        <table class="w-full text-left border-collapse">
                                            <thead>
                                                <tr class="text-[9px] uppercase tracking-widest text-slate-400 font-black border-b border-slate-100">
                                                    <th class="py-4 px-3 w-10">No</th>
                                                    <th class="py-4 px-3">Nama (Ref)</th>
                                                    <th class="py-4 px-3">Punggung</th>
                                                    <th class="py-4 px-3 text-center">Nomor</th>
                                                    <th class="py-4 px-3 text-center w-20">Size</th>
                                                    <th class="py-4 px-3">Upgrade Khusus</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-50">
                                                @foreach($item->roster as $pIdx => $player)
                                                <tr class="hover:bg-slate-50 transition-colors">
                                                    <td class="py-4 px-3 text-[10px] font-bold text-slate-300">{{ $pIdx+1 }}</td>
                                                    <td class="py-4 px-3 text-[11px] font-black text-slate-900 uppercase">{{ $player['ref_name'] ?? '-' }}</td>
                                                    <td class="py-4 px-3 text-[11px] font-black text-slate-900 uppercase">{{ $player['name'] ?? '-' }}</td>
                                                    <td class="py-4 px-3 text-[11px] font-black text-slate-900 text-center uppercase">{{ $player['number'] ?? '-' }}</td>
                                                    <td class="py-4 px-3 text-center">
                                                        <span class="px-2 py-1 rounded-md text-[10px] font-black {{ in_array($player['size'], ['XXL','XXXL']) ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700' }}">{{ $player['size'] }}</span>
                                                    </td>
                                                    <td class="py-4 px-3">
                                                        <div class="flex flex-wrap gap-1">
                                                            @if(isset($player['upgrade_names']) && count($player['upgrade_names']) > 0)
                                                                @foreach($player['upgrade_names'] as $uName)
                                                                    <span class="px-2 py-0.5 bg-brand-50 text-brand-900 text-[8px] font-black uppercase rounded shadow-sm border border-brand-100">{{ $uName }}</span>
                                                                @endforeach
                                                            @else
                                                                <span class="text-[9px] text-slate-300 italic font-bold">Standard</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Catatan Untuk Penjual</h2>
                    </div>
                    <div class="p-6 md:p-8">
                        <p class="text-sm font-bold text-slate-600 leading-relaxed italic">
                            @if($order->notes)
                                "{{ $order->notes }}"
                            @else
                                "Tidak ada catatan khusus untuk pesanan ini."
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 shadow-sm sticky top-32 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-[repeating-linear-gradient(45deg,#064e3b,#064e3b_10px,#fff_10px,#fff_20px,#ca8a04_20px,#ca8a04_30px,#fff_30px,#fff_40px)]"></div>
                    
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 pb-4 border-b border-slate-50 mt-2">Ringkasan Pembayaran</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                            <span>Total Pesanan</span>
                            <span class="text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                            <span>Sdh Dibayar</span>
                            <span class="text-green-600">- Rp {{ number_format($order->deposit_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-px bg-slate-100 w-full"></div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tagihan Tersisa</span>
                            <p class="text-3xl lg:text-4xl font-black text-brand-900 tracking-tighter leading-none mt-1">Rp {{ number_format($order->total_amount - $order->deposit_amount, 0, ',', '.') }}</p>
                        </div>

                        @if($order->payment_status === 'unpaid' && $order->status === 'unpaid')
                            <div class="mt-6 pt-6 border-t border-slate-50">
                                <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="w-full text-center text-[9px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors py-2">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    @if($order->payment_status === 'unpaid' && $order->status !== 'cancelled')
                        <div class="space-y-3 hidden md:block mt-6">
                            <a href="{{ route('customer.orders.edit', $order->order_number) }}" class="w-full flex items-center justify-center gap-2 py-4 bg-white border-2 border-slate-100 text-slate-600 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-slate-50 transition-all active:scale-95 mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit Detail Pesanan
                            </a>
                            <div class="h-px bg-slate-50 w-full my-4"></div>
                            <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="dp">
                                <button type="submit" class="w-full py-4 border-2 border-brand-900 text-brand-900 bg-white rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-50 transition-all active:scale-95 mb-2">
                                    Bayar DP 50% (Rp {{ number_format($order->total_amount / 2, 0, ',', '.') }})
                                </button>
                            </form>
                            <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="full">
                                <button type="submit" class="w-full py-4 bg-brand-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-800 transition-all shadow-lg shadow-brand-900/20 active:scale-95">
                                    Bayar Lunas Sekarang
                                </button>
                            </form>
                        </div>
                    @elseif($order->payment_status === 'partial')
                        @if(in_array($order->status, ['ready', 'shipped']))
                            <div class="space-y-4 mt-6">
                                <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl">
                                    <p class="text-[10px] font-black text-amber-900 uppercase tracking-widest leading-relaxed text-center">Pesanan Siap Dikirim / Sudah Dikirim!</p>
                                    <p class="text-[9px] font-bold text-amber-700 mt-1 text-center">Silakan lakukan pelunasan untuk menyelesaikan transaksi Anda.</p>
                                </div>
                                <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="rest">
                                    <button type="submit" class="w-full py-4 bg-brand-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-800 transition-all shadow-lg shadow-brand-900/20 active:scale-95">
                                        Bayar Pelunasan Sekarang
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="p-6 bg-brand-50 border border-brand-100 rounded-2xl text-center hidden md:block">
                                <p class="text-[10px] font-black text-brand-900 uppercase tracking-widest leading-relaxed">Deposit Diterima</p>
                                <p class="text-[10px] font-bold text-slate-500 mt-2">Pelunasan dapat dibayarkan saat barang selesai diproduksi.</p>
                            </div>
                        @endif
                    @endif

                    @if($order->status === 'shipped' || $order->status === 'completed')
                        <div class="mt-8 pt-8 border-t border-slate-50">
                             <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Informasi Pengiriman</h3>
                             <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                 <div class="flex justify-between items-center mb-2">
                                     <span class="text-[10px] font-bold text-slate-400 uppercase">Kurir</span>
                                     <span class="text-[10px] font-black text-slate-900 uppercase">{{ $order->courier_name ?? 'Reguler' }}</span>
                                 </div>
                                 <div class="flex justify-between items-center">
                                     <span class="text-[10px] font-bold text-slate-400 uppercase">No. Resi</span>
                                     <span class="text-[11px] font-black text-brand-900 uppercase tracking-wider">{{ $order->tracking_number ?? '-' }}</span>
                                 </div>
                             </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] pb-safe" x-data="{ showPayOptions: false }">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between gap-4">
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tagihan</span>
                <span class="text-lg font-black text-brand-900 tracking-tighter leading-none">Rp {{ number_format($order->total_amount - $order->deposit_amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex items-center gap-2">
                @if(($order->payment_status === 'unpaid' || ($order->payment_status === 'partial' && in_array($order->status, ['ready', 'shipped']))) && $order->status !== 'cancelled')
                    <div x-show="showPayOptions" x-transition class="absolute bottom-24 left-4 right-4 bg-white rounded-2xl shadow-2xl border border-slate-100 p-4 space-y-3">
                        @if($order->payment_status === 'unpaid')
                            <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="dp">
                                <button type="submit" class="w-full py-4 bg-white border-2 border-brand-900 text-brand-900 rounded-xl font-black uppercase tracking-widest text-[10px]">
                                    Bayar DP 50%
                                </button>
                            </form>
                            <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="full">
                                <button type="submit" class="w-full py-4 bg-brand-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px]">
                                    Bayar Lunas
                                </button>
                            </form>
                        @elseif($order->payment_status === 'partial')
                            <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="rest">
                                <button type="submit" class="w-full py-4 bg-brand-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px]">
                                    Bayar Pelunasan
                                </button>
                            </form>
                        @endif
                        
                        <button @click="showPayOptions = false" class="w-full text-center text-[9px] font-black text-slate-400 uppercase tracking-widest py-2">Batal</button>
                    </div>

                    @if($order->payment_status === 'unpaid')
                        <a href="{{ route('customer.orders.edit', $order->order_number) }}" class="flex items-center gap-2 px-3 py-3 bg-slate-50 text-slate-500 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span class="text-[9px] font-black uppercase tracking-widest">Edit</span>
                        </a>
                    @endif
                    
                    <button @click="showPayOptions = !showPayOptions" class="h-12 px-6 bg-brand-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-brand-900/20 active:scale-95 transition-all">
                        {{ $order->payment_status === 'partial' ? 'Pelunasan' : 'Bayar' }}
                    </button>
                @elseif($order->status !== 'cancelled')
                    <a href="https://wa.me/628123456789" target="_blank" class="h-12 px-8 bg-brand-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] flex items-center justify-center gap-2">
                        Bantuan
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('snapToken'))
            snap.pay('{{ session('snapToken') }}', {
                onSuccess: function(result){ window.location.href = "{{ route('customer.orders.show', $order->order_number) }}"; },
                onPending: function(result){ window.location.href = "{{ route('customer.orders.show', $order->order_number) }}"; },
                onError: function(result){ alert("Pembayaran Gagal!"); },
                onClose: function(){ }
            });
        @endif
    });
</script>
@endsection