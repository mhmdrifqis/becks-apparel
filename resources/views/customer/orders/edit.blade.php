@extends('layouts.main')

@section('title', 'Edit Pesanan #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-slate-50 pb-40" x-data="editOrderWizard()">
    <!-- Header: Pure White & Minimal -->
    <div class="bg-white border-b border-slate-100 pt-28 pb-6 md:pt-36 md:pb-10">
        <div class="max-w-7xl mx-auto px-4">
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <a href="{{ route('customer.orders') }}" class="hover:text-brand-900 transition-colors">Pesanan Saya</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-900">Edit Pesanan</span>
            </nav>
            <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Update Data <span class="text-brand-600">Pesanan</span>
            </h1>
            <p class="mt-3 text-slate-400 font-bold uppercase tracking-widest text-[10px] md:text-xs">Nomor Pesanan: #{{ $order->order_number }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <form action="{{ route('customer.orders.update-detailed', $order->id) }}" method="POST" enctype="multipart/form-data" id="orderForm">
            @csrf
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-xs font-black text-rose-900 uppercase tracking-widest">Ada kendala saat memperbarui data</h3>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-[11px] font-bold text-rose-600 uppercase tracking-wide">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="space-y-6">
                <!-- 1. Alamat Pengiriman (Becks Style) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 relative">
                    <div class="h-1 w-full bg-[repeating-linear-gradient(45deg,#064e3b,#064e3b_10px,#fff_10px,#fff_20px,#ca8a04_20px,#ca8a04_30px,#fff_30px,#fff_40px)]"></div>
                    
                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <svg class="w-5 h-5 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <h2 class="text-xs font-black text-brand-900 uppercase tracking-widest">Informasi Penerima & Alamat</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Penerima</label>
                                <input type="text" name="recipient_name" value="{{ $order->recipient_name }}" required class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900" placeholder="Nama Lengkap">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nomor Telepon / WA</label>
                                <input type="text" name="recipient_phone" value="{{ $order->recipient_phone }}" required class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Pengiriman</label>
                            <textarea name="shipping_address" required rows="3" class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900" placeholder="Nama Jalan, Gedung, RT/RW, Kecamatan, Kota/Kab, Kodepos...">{{ $order->shipping_address }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Daftar Produk Dipesan -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Produk Dipesan</h2>
                    </div>

                    <div class="divide-y divide-slate-50">
                        <template x-for="(item, idx) in items" :key="item.id">
                            <div class="p-6 md:p-8 space-y-8">
                                <!-- Item Header -->
                                <div class="flex gap-4 md:gap-6">
                                    <div class="w-20 h-20 md:w-24 md:h-24 bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden shrink-0">
                                        <img :src="item.img" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm md:text-lg font-black text-slate-900 uppercase tracking-tight truncate mb-1" x-text="item.name"></h3>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Variasi: <span class="text-brand-900" x-text="item.matName"></span></span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Qty: <span class="text-brand-900" x-text="item.qty + ' Pcs'"></span></span>
                                        </div>
                                        <p class="mt-2 text-sm font-black text-slate-900" x-text="formatRupiah(item.basePrice)"></p>
                                    </div>
                                </div>

                                <!-- Section 1: Lampiran Desain -->
                                <div class="pt-6 border-t border-slate-50 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">1. Lampiran Desain / Referensi</h4>
                                        <div class="flex gap-2 p-1 bg-slate-100 rounded-xl">
                                            <button type="button" @click="item.designMethod = 'upload'" class="px-4 py-1.5 text-[9px] font-black uppercase rounded-lg transition-all" :class="item.designMethod === 'upload' ? 'bg-white shadow-sm text-brand-900' : 'text-slate-400'">Upload</button>
                                            <button type="button" @click="item.designMethod = 'customizer'" class="px-4 py-1.5 text-[9px] font-black uppercase rounded-lg transition-all" :class="item.designMethod === 'customizer' ? 'bg-white shadow-sm text-brand-900' : 'text-slate-400'">Web Design</button>
                                        </div>
                                    </div>

                                    <div x-show="item.designMethod === 'upload'" x-transition>
                                        <div class="p-4 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50/50">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <button type="button" @click="document.getElementById('fileInput'+item.id).click()" class="px-6 py-2.5 bg-brand-900 text-white text-[10px] font-black uppercase rounded-xl hover:bg-brand-800 transition-all flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                    Pilih File
                                                </button>
                                                <input type="file" :id="'fileInput'+item.id" @change="handleFileSelect(item, $event)" multiple class="hidden" :name="'designs['+item.id+'][files][]'">
                                                <template x-if="item.designFiles.length === 0">
                                                    <span class="text-[10px] font-bold text-slate-400">Belum ada file dipilih</span>
                                                </template>
                                            </div>

                                            <div class="mt-4 flex flex-wrap gap-2" x-show="item.designFiles.length > 0">
                                                <template x-for="(file, fIdx) in item.designFiles" :key="fIdx">
                                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-100 rounded-lg shadow-sm">
                                                        <span class="text-[9px] font-bold text-slate-600 truncate max-w-[150px]" x-text="file.name"></span>
                                                        <button type="button" @click="removeDesignFile(item, fIdx)" class="text-slate-400 hover:text-red-500 transition-colors">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                            <p class="text-[8px] text-slate-400 mt-4 italic">* Anda dapat memilih lebih dari satu file (Gambar, PDF, Zip).</p>
                                        </div>
                                    </div>

                                    <div x-show="item.designMethod === 'customizer'" x-transition class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih Desain Tersimpan</label>
                                            <button type="button" x-show="item.saved_id" @click="item.saved_id = null" class="text-[9px] font-black text-rose-500 uppercase tracking-widest hover:underline flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Hapus Pilihan
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 max-h-[320px] overflow-y-auto p-1 custom-scrollbar">
                                            @foreach($userDesigns as $design)
                                                <label class="relative cursor-pointer group">
                                                    <input type="radio" :name="'designs['+item.id+'][saved_id]'" value="{{ $design->id }}" x-model="item.saved_id" class="peer absolute opacity-0">
                                                    <div class="bg-white border-2 border-slate-100 rounded-2xl p-2 group-hover:border-brand-900 peer-checked:border-brand-900 peer-checked:bg-brand-50/50 transition-all duration-300 h-full flex flex-col">
                                                        <div class="aspect-square bg-slate-50 rounded-xl overflow-hidden mb-2 relative shrink-0">
                                                            @if($design->preview_path)
                                                                <img src="{{ Storage::url($design->preview_path) }}" class="w-full h-full object-contain">
                                                            @else
                                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                                </div>
                                                            @endif
                                                            <div class="absolute inset-0 bg-brand-900/0 group-hover:bg-brand-900/5 transition-colors"></div>
                                                        </div>
                                                        <p class="text-[9px] font-black text-slate-900 uppercase truncate text-center mt-auto px-1" title="{{ $design->name }}">{{ $design->name }}</p>
                                                    </div>
                                                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-brand-900 text-white rounded-full flex items-center justify-center scale-0 peer-checked:scale-100 transition-transform duration-300 shadow-lg z-10">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        @if(count($userDesigns) === 0)
                                            <div class="p-8 bg-slate-50 rounded-2xl text-center border border-dashed border-slate-200">
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Belum ada desain tersimpan</p>
                                                <a href="{{ route('customizer') }}" target="_blank" class="text-[9px] font-black text-brand-900 uppercase tracking-widest hover:underline">Buka Customizer →</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Section 2: Upgrade Global -->
                                <div class="pt-6 border-t border-slate-50 space-y-4">
                                    <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">2. Upgrade Global (Untuk Semua Pemain)</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($upgrades as $category => $list)
                                            <div class="space-y-3">
                                                <p class="text-[8px] font-black text-brand-900 uppercase tracking-widest mb-2 border-b border-slate-100 pb-1">{{ $category }}</p>
                                                <div class="space-y-2">
                                                    @foreach($list as $upgrade)
                                                        <label class="flex items-start gap-2 cursor-pointer group">
                                                            <div class="pt-0.5">
                                                                <input type="checkbox" :name="'upgrades['+item.id+'][]'" value="{{ $upgrade->id }}" x-model="item.globalUpgrades" class="w-4 h-4 text-brand-900 border-slate-300 rounded focus:ring-brand-900">
                                                            </div>
                                                            <div class="flex-1">
                                                                <p class="text-[10px] font-bold text-slate-700 group-hover:text-brand-900 transition-colors leading-tight">{{ $upgrade->name }}</p>
                                                                <p class="text-[9px] text-amber-600 font-black mt-0.5">+ Rp {{ number_format($upgrade->price, 0, ',', '.') }}</p>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-[8px] text-slate-400 mt-2 italic">* Upgrade ini berlaku otomatis untuk setiap baju dalam daftar roster.</p>
                                </div>                                <!-- 3. Roster Pemain (Refined Columns + Upgrade Column) -->
                                <div class="pt-6 border-t border-slate-50 relative">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">3. List Pemain & Upgrade Khusus</h4>
                                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest" x-show="getItemRosterSurcharge(item) > 0" x-text="'+ Rp ' + formatRupiah(getItemRosterSurcharge(item))"></span>
                                    </div>
                                    
                                    <div class="relative">
                                        <div class="inline-block min-w-full align-middle">
                                            <table class="min-w-full divide-y divide-slate-100 border border-slate-100 rounded-xl bg-white">
                                                <thead class="bg-slate-50">
                                                    <tr class="text-[9px] font-black text-slate-400 uppercase tracking-widest text-left">
                                                        <th class="px-4 py-3 w-8">No</th>
                                                        <th class="px-4 py-3">Nama (Ref)</th>
                                                        <th class="px-4 py-3">Nama Punggung</th>
                                                        <th class="px-4 py-3 w-20">No</th>
                                                        <th class="px-4 py-3 w-24">Ukuran</th>
                                                        <th class="px-4 py-3">Upgrade</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-50">
                                                    <template x-for="(player, pIdx) in item.roster" :key="pIdx">
                                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                                            <td class="px-2 py-3 text-[9px] font-black text-slate-300 text-center" x-text="pIdx + 1"></td>
                                                            <td class="px-2 py-3">
                                                                <input type="text" x-model="player.ref_name" :name="'roster['+item.id+']['+pIdx+'][ref_name]'" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-[10px] font-bold focus:ring-brand-900" placeholder="Pemain 1">
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <input type="text" x-model="player.name" :name="'roster['+item.id+']['+pIdx+'][name]'" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-[10px] font-bold focus:ring-brand-900" placeholder="BECCKS">
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <input type="text" x-model="player.number" :name="'roster['+item.id+']['+pIdx+'][number]'" class="w-12 bg-white border border-slate-200 rounded-lg px-2 py-2 text-[10px] font-bold text-center focus:ring-brand-900" placeholder="00">
                                                            </td>
                                                            <td class="px-2 py-3">
                                                                <select x-model="player.size" :name="'roster['+item.id+']['+pIdx+'][size]'" class="w-full bg-white border border-slate-200 rounded-lg px-1 py-2 text-[10px] font-bold focus:ring-brand-900">
                                                                    <option value="S">S</option>
                                                                    <option value="M">M</option>
                                                                    <option value="L">L</option>
                                                                    <option value="XL">XL</option>
                                                                    <option value="XXL">XXL (+5k)</option>
                                                                    <option value="XXXL">3XL (+10k)</option>
                                                                </select>
                                                            </td>
                                                            <td class="px-2 py-3 relative">
                                                                <!-- Multi-Upgrade Trigger -->
                                                                <button type="button" @click="player.showUpgrades = !player.showUpgrades" 
                                                                        class="w-full inline-flex items-center justify-between gap-2 px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg hover:border-brand-900/30 transition-all text-left">
                                                                    <span class="text-[9px] text-slate-400 font-bold uppercase truncate" x-text="player.upgrades.length > 0 ? player.upgrades.length + ' Dipilih' : 'Pilih Upgrade'"></span>
                                                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                                                </button>

                                                                <!-- Upgrade Selection Popover -->
                                                                <div x-show="player.showUpgrades" 
                                                                     @click.away="player.showUpgrades = false"
                                                                     class="absolute z-[60] right-0 mt-2 w-[280px] bg-white border border-slate-100 shadow-[0_10px_40px_rgba(0,0,0,0.15)] rounded-2xl p-4">
                                                                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Ekstra Kustom</h5>
                                                                    <div class="space-y-4 max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                                                                        @foreach($upgrades as $category => $list)
                                                                            <div>
                                                                                <p class="text-[8px] font-black text-brand-900 uppercase tracking-widest mb-2 border-b border-slate-50">{{ $category }}</p>
                                                                                <div class="grid grid-cols-1 gap-1.5">
                                                                                    @foreach($list as $u)
                                                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                                                            <input type="checkbox" x-model="player.upgrades" value="{{ $u->id }}" :name="'roster['+item.id+']['+pIdx+'][upgrades][]'" class="w-4 h-4 text-brand-900 border-slate-200 rounded">
                                                                                            <div class="flex-1 min-w-0">
                                                                                                <p class="text-[9px] font-bold text-slate-700 truncate leading-tight">{{ $u->name }}</p>
                                                                                                <p class="text-[8px] text-amber-600 font-black">+{{ number_format($u->price/1000, 0) }}k</p>
                                                                                            </div>
                                                                                        </label>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button" @click="player.showUpgrades = false" class="w-full mt-4 py-2 bg-brand-900 text-white text-[9px] font-black uppercase rounded-lg">Selesai</button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 3. Catatan Pesanan -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Catatan Pesanan</h2>
                    </div>
                    <div class="p-6 md:p-8">
                        <textarea name="notes" rows="4" class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-6 py-4 focus:ring-brand-900 focus:border-brand-900" placeholder="Tulis instruksi khusus untuk kami di sini...">{{ $order->notes }}</textarea>
                    </div>
                </div>

                <!-- 4. Rincian Pembayaran -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Rincian Pembayaran</h2>
                    </div>
                    <div class="p-6 md:p-8 space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium">Subtotal Produk</span>
                            <span class="text-slate-900 font-bold" x-text="'Rp ' + formatRupiah(subtotal)"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium">Total Tambahan (Roster & Upgrade)</span>
                            <span class="text-brand-900 font-black" x-text="'+ Rp ' + formatRupiah(totalRosterSurcharge)"></span>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-sm md:text-lg font-black text-slate-900 uppercase tracking-tight">Total Pembayaran</span>
                            <span class="text-xl md:text-3xl font-black text-brand-900 tracking-tighter" x-text="'Rp ' + formatRupiah(grandTotal)"></span>
                        </div>
                        <div class="h-px bg-slate-100 w-full my-4"></div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-xs font-black text-slate-900 uppercase tracking-widest">Total Bayar</span>
                            <span class="text-2xl font-black text-brand-900 tracking-tighter" x-text="'Rp ' + formatRupiah(grandTotal)"></span>
                        </div>

                        <!-- Desktop Submit Button -->
                        <button type="submit" class="hidden md:block w-full py-5 bg-brand-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/20 active:scale-95">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('customer.orders.show', $order->order_number) }}" class="block w-full text-center mt-4 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
                            Batal & Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Payment Bar (Mobile Only) -->
            <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-100 shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
                <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bayar</span>
                        <span class="text-xl font-black text-brand-900 tracking-tighter" x-text="'Rp ' + formatRupiah(grandTotal)"></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('customer.orders.show', $order->order_number) }}" class="flex px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="h-12 px-8 bg-brand-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-800 active:scale-95 transition-all shadow-xl shadow-brand-900/20 whitespace-nowrap">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function editOrderWizard() {
        return {
            availableUpgrades: {
                @foreach(\App\Models\Upgrade::all() as $u)
                "{{ $u->id }}": {{ $u->price }},
                @endforeach
            },
            items: [
                @foreach($order->orderItems as $item)
                @php 
                    $src = count($item->package->images ?? []) > 0 ? (str_starts_with($item->package->images[0], 'assets/') ? asset($item->package->images[0]) : Storage::url($item->package->images[0])) : ''; 
                    $existingMethod = $item->design ? (isset($item->design->design_json['files']) ? "upload" : "customizer") : "upload";
                @endphp
                {
                    id: {{ $item->id }},
                    name: '{{ addslashes($item->package->name) }}',
                    matName: '{{ $item->material->name }}',
                    basePrice: {{ $item->package->base_price + ($item->material?->additional_price ?? 0) }},
                    qty: {{ $item->quantity }},
                    img: '{{ $src }}',
                    designMethod: '{{ $existingMethod }}',
                    saved_id: {{ $item->design_id ?? 'null' }},
                    globalUpgrades: @json($item->upgrades->pluck('id')),
                    designFiles: [], 
                    roster: (@json($item->roster)).map(p => ({
                        ...p,
                        upgrades: p.upgrades || [],
                        showUpgrades: false
                    }))
                },
                @endforeach
            ],
            
            handleFileSelect(item, event) {
                const files = event.target.files;
                for (let i = 0; i < files.length; i++) {
                    item.designFiles.push(files[i]);
                }
                this.syncFiles(item);
                event.target.value = '';
            },

            removeDesignFile(item, index) {
                item.designFiles.splice(index, 1);
                this.syncFiles(item);
            },

            syncFiles(item) {
                const dt = new DataTransfer();
                item.designFiles.forEach(file => dt.items.add(file));
                const input = document.getElementById('fileInput' + item.id);
                if (input) {
                    input.files = dt.files;
                }
            },

            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            },
            
            getItemRosterSurcharge(item) {
                let globalSurcharge = item.globalUpgrades.reduce((sum, uId) => {
                    return sum + (this.availableUpgrades[uId] || 0);
                }, 0) * item.qty;

                let rosterSurcharge = item.roster.reduce((sum, player) => {
                    let surcharge = 0;
                    if (player.size === 'XXL') surcharge += 5000;
                    if (player.size === 'XXXL') surcharge += 10000;
                    
                    (player.upgrades || []).forEach(uId => {
                        surcharge += (this.availableUpgrades[uId] || 0);
                    });
                    
                    return sum + surcharge;
                }, 0);

                return globalSurcharge + rosterSurcharge;
            },
            
            get totalRosterSurcharge() {
                return this.items.reduce((sum, item) => sum + this.getItemRosterSurcharge(item), 0);
            },
            
            get subtotal() {
                return this.items.reduce((sum, item) => sum + (item.basePrice * item.qty), 0);
            },
            
            get grandTotal() {
                return this.subtotal + this.totalRosterSurcharge;
            }
        }
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection