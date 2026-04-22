@extends('layouts.main')

@section('title', 'Detail Pesanan ' . $order->order_number . ' - Becks Apparel')

@section('content')
<div class="min-h-screen bg-slate-50 pb-40">
    <!-- Header Hero Section -->
    <div class="bg-white border-b border-slate-100 pt-28 pb-10 md:pt-36 md:pb-16 relative overflow-hidden">
        <!-- Abstract Background Decoration -->
        <div class="absolute top-0 right-0 w-1/3 h-full bg-brand-50/20 skew-x-12 translate-x-20 pointer-events-none"></div>
        
        <div class="max-w-5xl mx-auto px-4 relative z-10">
            <a href="{{ route('customer.orders') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-brand-900 transition-colors mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Pesanan
            </a>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-brand-900 text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Invoice</span>
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">#{{ $order->order_number }}</span>
                    </div>
                    <h1 class="text-3xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase leading-none">
                        Rincian <span class="text-brand-600">Pesanan</span> Anda
                    </h1>
                    <p class="mt-4 text-sm font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $order->created_at->format('d F Y, H:i') }} WIB
                    </p>
                </div>
                
                <div class="flex flex-col items-end gap-3">
                    <div class="flex items-center gap-2">
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

    <div class="max-w-5xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Order Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 1. Alamat Pengiriman (Shopee Style) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                    <div class="h-1 w-full bg-[repeating-linear-gradient(45deg,#064e3b,#064e3b_10px,#fff_10px,#fff_20px,#ca8a04_20px,#ca8a04_30px,#fff_30px,#fff_40px)]"></div>
                    <div class="p-8">
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

                <!-- 2. Daftar Item Pesanan -->
                <div class="space-y-6">
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Daftar Produk</h2>
                    @foreach($order->orderItems as $item)
                        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden" x-data="{ openRoster: false }">
                            <div class="p-6 md:p-8">
                                <div class="flex gap-6">
                                    <!-- Image Preview -->
                                    <div class="w-24 h-24 md:w-32 md:h-32 bg-slate-50 rounded-2xl border border-slate-100 shrink-0 overflow-hidden group relative">
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
                                        <h3 class="text-lg md:text-2xl font-black text-slate-900 uppercase tracking-tight truncate mb-2">{{ $item->package->name }}</h3>
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

                                <!-- Design Files Section (Handles multiple) -->
                                <div class="mt-8 pt-8 border-t border-slate-50">
                                    <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Lampiran Desain / Referensi</h4>
                                    <div class="flex flex-wrap gap-4">
                                        @if($item->design)
                                            @php 
                                                $designJson = $item->design->design_json;
                                                $files = $designJson['files'] ?? (isset($designJson['file']) ? [$designJson['file']] : []);
                                            @endphp
                                            @forelse($files as $file)
                                                <a href="{{ Storage::url($file) }}" target="_blank" class="w-20 h-20 bg-slate-50 rounded-xl border-2 border-brand-100 overflow-hidden hover:border-brand-900 transition-all group relative">
                                                    <img src="{{ Storage::url($file) }}" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-brand-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    </div>
                                                </a>
                                            @empty
                                                @if(isset($designJson['type']) && $designJson['type'] === 'customizer')
                                                    <div class="px-6 py-3 bg-brand-50 border border-brand-100 rounded-xl">
                                                        <p class="text-[10px] font-black text-brand-900 uppercase tracking-widest">🛠 Menggunakan Web Customizer</p>
                                                    </div>
                                                @else
                                                    <p class="text-[10px] font-bold text-slate-400 italic">Tidak ada file lampiran.</p>
                                                @endif
                                            @endforelse
                                        @else
                                            <p class="text-[10px] font-bold text-slate-400 italic">Desain belum ditentukan.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Roster -->
                            <div x-show="openRoster" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                                <div class="bg-white rounded-[2.5rem] w-full max-w-5xl max-h-[85vh] overflow-hidden shadow-2xl flex flex-col" @click.away="openRoster = false">
                                    <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                        <div>
                                            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Detail Roster Tim</h2>
                                            <p class="text-xs font-bold text-slate-500 uppercase mt-1">{{ $item->package->name }} - {{ $item->quantity }} Pcs</p>
                                        </div>
                                        <button @click="openRoster = false" class="p-3 bg-white rounded-2xl border border-slate-200 text-slate-400 hover:text-red-500 transition-colors">
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

                <!-- 3. Catatan Pesanan -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Catatan Untuk Penjual</h2>
                    </div>
                    <div class="p-8">
                        <p class="text-sm font-bold text-slate-600 leading-relaxed italic">
                            @if($order->notes)
                                "{{ $order->notes }}"
                            @else
                                "Tidak ada catatan khusus untuk pesanan ini."
                            @endif
                        </p>
                    </div>
                </div>

                <!-- 4. Lacak Produksi (Timeline) -->
                @if(in_array($order->payment_status, ['partial', 'paid']))
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 md:p-10">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-10 pb-4 border-b border-slate-50 flex items-center gap-3">
                         <svg class="w-5 h-5 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tracking Produksi Pabrik
                    </h3>
                    
                    <div class="relative pl-8 space-y-12 border-l-2 border-slate-100 ml-4">
                        @php
                            $stages = ['pending' => 'Konfirmasi Admin', 'printing' => 'Printing Sublimasi', 'sewing' => 'Proses Penjahitan', 'qc' => 'Quality Control', 'ready' => 'Siap Dikirim', 'shipped' => 'Dalam Pengiriman', 'completed' => 'Selesai'];
                            $currentStageIdx = array_search($order->status, array_keys($stages));
                            if ($currentStageIdx === false) $currentStageIdx = 0;
                        @endphp
                        
                        @foreach($stages as $stageKey => $stageName)
                            @php
                                $thisIdx = array_search($stageKey, array_keys($stages));
                                $isCompleted = $thisIdx < $currentStageIdx;
                                $isCurrent = $thisIdx === $currentStageIdx;
                            @endphp
                            <div class="relative">
                                <!-- Node -->
                                <div class="absolute -left-[45px] top-0 w-8 h-8 rounded-2xl border-4 transition-all duration-500
                                    {{ $isCompleted ? 'bg-brand-900 border-white shadow-xl shadow-brand-900/20' : ($isCurrent ? 'bg-amber-500 border-white shadow-lg shadow-amber-500/30 animate-pulse' : 'bg-slate-200 border-white') }}">
                                    @if($isCompleted)
                                        <svg class="w-full h-full p-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <p class="text-xs font-black tracking-widest uppercase {{ $isCompleted || $isCurrent ? 'text-slate-900' : 'text-slate-300' }}">{{ $stageName }}</p>
                                    @if($isCurrent)
                                        <span class="mt-2 inline-flex items-center text-[9px] font-black bg-amber-100 text-amber-700 px-2 py-0.5 rounded uppercase tracking-tighter w-fit">Tahap Aktif</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Final Invoice Widget -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-2xl shadow-slate-200/50 sticky top-32 overflow-hidden">
                    <!-- Ribbon Decoration -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-[repeating-linear-gradient(45deg,#064e3b,#064e3b_10px,#fff_10px,#fff_20px,#ca8a04_20px,#ca8a04_30px,#fff_30px,#fff_40px)]"></div>
                    
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-10 pb-4 border-b border-slate-50">Ringkasan Pembayaran</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                            <span>Total Pesanan</span>
                            <span class="text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                            <span>Sdh Dibayar</span>
                            <span class="text-green-600">- Rp {{ number_format($order->deposit_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-px bg-slate-100 w-full"></div>
                        <div class="pt-2">
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Tagihan Tersisa</p>
                            <p class="text-4xl font-black text-brand-900 tracking-tighter leading-none">Rp {{ number_format($order->total_amount - $order->deposit_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($order->payment_status === 'unpaid' && $order->status !== 'cancelled')
                        <div class="space-y-4">
                            <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="dp">
                                <button type="submit" class="w-full py-5 border-2 border-brand-900 text-brand-900 bg-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-50 transition-all active:scale-95 mb-4">
                                    Bayar DP 50% (Rp {{ number_format($order->total_amount / 2, 0, ',', '.') }})
                                </button>
                            </form>
                            <form action="{{ route('payment.create', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="full">
                                <button type="submit" class="w-full py-5 bg-brand-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/30 active:scale-95">
                                    Bayar Lunas Sekarang
                                </button>
                            </form>
                        </div>
                        <div class="mt-8 flex flex-col gap-2">
                             <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                @csrf
                                <button type="submit" class="w-full text-center text-[9px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors py-2">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        </div>
                    @elseif($order->payment_status === 'partial')
                        <div class="p-6 bg-brand-50 border border-brand-100 rounded-3xl text-center">
                            <p class="text-xs font-black text-brand-900 uppercase tracking-widest leading-relaxed">Deposit Diterima</p>
                            <p class="text-[10px] font-bold text-slate-500 mt-2">Pelunasan dapat dibayarkan saat barang selesai diproduksi.</p>
                        </div>
                        @if(in_array($order->status, ['ready', 'shipped', 'completed']))
                            <form action="{{ route('payment.create', $order->id) }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="type" value="rest">
                                <button type="submit" class="w-full py-5 bg-brand-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/30 active:scale-95">
                                    Lunasi Tagihan Akhir
                                </button>
                            </form>
                        @endif
                    @elseif($order->payment_status === 'paid')
                        <div class="p-8 bg-green-50 border border-green-100 rounded-[2rem] text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm font-black text-green-700 uppercase tracking-widest">Tagihan Lunas</p>
                            <p class="text-[10px] text-green-600/70 font-bold mt-1 uppercase">Terima Kasih!</p>
                        </div>
                    @endif
                </div>
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
