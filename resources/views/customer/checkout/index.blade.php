@extends('layouts.main')

@section('title', 'Checkout - Becks Apparel')

@section('content')
<div x-data="checkoutWizard()" class="min-h-screen bg-slate-50 pb-40">
    <!-- Header: Pure White & Minimal -->
    <div class="bg-white border-b border-slate-100 pt-28 pb-6 md:pt-36 md:pb-10">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Checkout <span class="text-brand-600">Pesanan</span>
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" id="checkoutForm" @submit.prevent="submitForm">
            @csrf
            
            <div class="space-y-6">
                <!-- 1. Alamat Pengiriman (Editable Becks Style) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                    <!-- Becks Ribbon decoration (Green & Gold) -->
                    <div class="h-1 w-full bg-[repeating-linear-gradient(45deg,#064e3b,#064e3b_10px,#fff_10px,#fff_20px,#ca8a04_20px,#ca8a04_30px,#fff_30px,#fff_40px)]"></div>
                    
                    <!-- Persistent Address Inputs (Hidden when not editing, but always submitted) -->
                    <input type="hidden" name="recipient_name" :value="recipient_name" required>
                    <input type="hidden" name="recipient_phone" :value="recipient_phone" required>
                    <input type="hidden" name="shipping_address" :value="shipping_address" required>

                    <div class="p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <h2 class="text-xs font-black text-brand-900 uppercase tracking-widest">Alamat Pengiriman</h2>
                            </div>
                            <button type="button" @click="addressEditing = true" x-show="!addressEditing" class="text-[10px] font-black text-brand-900 uppercase tracking-widest hover:underline">Ubah</button>
                        </div>

                        <!-- View Mode -->
                        <div x-show="!addressEditing" class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-black text-slate-900 uppercase" x-text="recipient_name || 'Nama Belum Diisi'"></span>
                                <span class="text-slate-300">|</span>
                                <span class="text-sm font-bold text-slate-500" x-text="recipient_phone || 'No. Telp Belum Diisi'"></span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed" x-text="shipping_address || 'Silakan lengkapi alamat pengiriman Anda.'"></p>
                        </div>

                        <!-- Edit Mode -->
                        <div x-show="addressEditing" x-transition class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Penerima</label>
                                    <input type="text" x-model="recipient_name" class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">No. Telepon / WA</label>
                                    <input type="text" x-model="recipient_phone" class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap (Jl, No Rumah, Kec, Kota)</label>
                                <textarea x-model="shipping_address" rows="3" class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="addressEditing = false" class="px-6 py-2 bg-brand-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-brand-900/20">Simpan Alamat</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Daftar Produk Dipesan -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-visible">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 rounded-t-2xl">
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

                                <!-- 1. Lampiran Desain (Multiple Upload Support) -->
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
                                            <!-- Custom File Trigger -->
                                            <div class="flex flex-wrap items-center gap-3">
                                                <button type="button" @click="document.getElementById('fileInput'+item.id).click()" class="px-6 py-2.5 bg-brand-900 text-white text-[10px] font-black uppercase rounded-xl hover:bg-brand-800 transition-all flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                    Pilih File
                                                </button>
                                                <input type="file" 
                                                       :id="'fileInput'+item.id"
                                                       @change="handleFileSelect(item, $event)" 
                                                       multiple 
                                                       class="hidden" 
                                                       :name="'designs['+item.id+'][files][]'">
                                                
                                                <template x-if="item.designFiles.length === 0">
                                                    <span class="text-[10px] font-bold text-slate-400">Belum ada file dipilih</span>
                                                </template>
                                            </div>

                                            <!-- File List Preview -->
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
                                    <div x-show="item.designMethod === 'customizer'" x-transition>
                                        <select :name="'designs['+item.id+'][saved_id]'" class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-[10px] font-bold rounded-xl px-4 py-3 focus:ring-brand-900 uppercase">
                                            <option value="">-- Pilih Desain Tersimpan --</option>
                                            @foreach(\App\Models\Design::where('user_id', Auth::id())->latest()->get() as $design)
                                                <option value="{{ $design->id }}">Design #{{ $design->id }} ({{ $design->created_at->format('d M') }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- 2. Upgrade Global (Untuk Seluruh Pemain) -->
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
                                                                <input type="checkbox" x-model="item.globalUpgrades" value="{{ $upgrade->id }}" :name="'upgrades['+item.id+'][]'" class="w-4 h-4 text-brand-900 border-slate-300 rounded focus:ring-brand-900">
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
                                </div>

                                <!-- 3. Roster Pemain (Refined Columns + Upgrade Column) -->
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
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">3. Catatan Pesanan</h2>
                    </div>
                    <div class="p-6 md:p-8">
                        <textarea name="notes" rows="4" class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-6 py-4 focus:ring-brand-900 focus:border-brand-900" placeholder="Tulis instruksi khusus untuk kami di sini (Contoh: Titip logo di bagian depan, kerah warna hitam, dll)"></textarea>
                    </div>
                </div>

                <!-- 4. Rincian Pembayaran -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Rincian Pembayaran</h2>
                    </div>
                    <div class="p-6 md:p-8 space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium">Subtotal Produk</span>
                            <span class="text-slate-900 font-bold" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium">Total Tambahan (Roster & Upgrade)</span>
                            <span class="text-brand-900 font-black" x-text="'+ Rp ' + formatRupiah(totalRosterSurcharge)"></span>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-sm md:text-lg font-black text-slate-900 uppercase tracking-tight">Total Pembayaran</span>
                            <span class="text-xl md:text-3xl font-black text-brand-900 tracking-tighter" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                        <div class="h-px bg-slate-100 w-full my-4"></div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-xs font-black text-slate-900 uppercase tracking-widest">Total Bayar</span>
                            <span class="text-2xl font-black text-brand-900 tracking-tighter" x-text="formatRupiah(grandTotal)"></span>
                        </div>

                        <!-- Desktop Submit Button -->
                        <button type="submit" class="hidden md:block w-full py-5 bg-brand-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/20 active:scale-95">
                            Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Payment Bar (Mobile Only) -->
            <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-100 shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
                <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bayar</span>
                        <span class="text-xl font-black text-brand-900 tracking-tighter" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                    <button type="submit" class="h-12 px-8 bg-brand-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-brand-800 active:scale-95 transition-all shadow-xl shadow-brand-900/20 whitespace-nowrap">
                        Pesan Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function checkoutWizard() {
        return {
            recipient_name: '{{ Auth::user()->name }}',
            recipient_phone: '{{ Auth::user()->phone ?? "" }}',
            shipping_address: '',
            addressEditing: false,
            isSubmitting: false,

            availableUpgrades: {
                @foreach(\App\Models\Upgrade::all() as $u)
                "{{ $u->id }}": {{ $u->price }},
                @endforeach
            },
            items: [
                @foreach($cartItems as $item)
                @php 
                    $src = count($item->package->images ?? []) > 0 ? (str_starts_with($item->package->images[0], 'assets/') ? asset($item->package->images[0]) : Storage::url($item->package->images[0])) : ''; 
                @endphp
                {
                    id: {{ $item->id }},
                    name: '{{ addslashes($item->package->name) }}',
                    basePrice: {{ $item->package->base_price + ($item->material?->additional_price ?? 0) }},
                    qty: {{ $item->quantity }},
                    matName: '{{ $item->material->name ?? "-" }}',
                    img: '{{ $src }}',
                    designMethod: 'upload',
                    globalUpgrades: [],
                    designFiles: [], // List of File objects
                    roster: Array.from({ length: {{ $item->quantity }} }, () => ({
                        ref_name: '',
                        name: '',
                        number: '',
                        size: 'L',
                        upgrades: [],
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
                // Clear input so same file can be re-selected if removed
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
                // Global Upgrades Surcharge (Price * Qty)
                let globalSurcharge = item.globalUpgrades.reduce((sum, uId) => {
                    return sum + (this.availableUpgrades[uId] || 0);
                }, 0) * item.qty;

                // Per Player Surcharge (Size + Player-specific Upgrades)
                let rosterSurcharge = item.roster.reduce((sum, player) => {
                    let surcharge = 0;
                    // Size surcharge
                    if (player.size === 'XXL') surcharge += 5000;
                    if (player.size === 'XXXL') surcharge += 10000;
                    
                    // Upgrades surcharge (per player)
                    player.upgrades.forEach(uId => {
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
            },

            async submitForm() {
                if (this.isSubmitting) return;

                // Validation
                if (!this.recipient_name || !this.recipient_phone || !this.shipping_address) {
                    this.addressEditing = true;
                    
                    // Use SweetAlert if available, otherwise fallback to alert
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Data Belum Lengkap',
                            text: 'Silakan isi Nama, Nomor HP, dan Alamat Pengiriman Anda.',
                            icon: 'warning',
                            confirmButtonColor: '#064e3b',
                            confirmButtonText: 'OKE'
                        });
                    } else {
                        alert('Silakan lengkapi Nama, Nomor HP, dan Alamat Pengiriman Anda sebelum membuat pesanan.');
                    }
                    return;
                }

                // Proceed to submit
                this.isSubmitting = true;
                document.getElementById('checkoutForm').submit();
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
