@extends('layouts.main')

@section('title', 'Edit Pesanan ' . $order->order_number . ' - Becks Apparel')

@section('content')
<div class="min-h-screen bg-slate-50 pb-32" x-data="editOrderWizard()">
    <!-- Header: Pure White & Minimal -->
    <div class="bg-white border-b border-slate-100 pt-28 pb-6 md:pt-36 md:pb-10">
        <div class="max-w-7xl mx-auto px-4">
            <a href="{{ route('customer.orders.show', $order->order_number) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-brand-900 transition-colors mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Rincian Pesanan
            </a>
            <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit <span class="text-brand-600">Pesanan</span>
            </h1>
            <p class="text-xs font-bold text-slate-300 uppercase tracking-widest mt-2">#{{ $order->order_number }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
        <form action="{{ route('customer.orders.update-detailed', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Data Roster & Desain -->
                <div class="lg:col-span-2 space-y-10">
                    
                    <!-- Informasi Pengiriman -->
                    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl overflow-hidden p-6 md:p-8">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Informasi Pengiriman
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Penerima</label>
                                <input type="text" name="recipient_name" value="{{ $order->recipient_name }}" required class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900 shadow-sm" placeholder="Nama Lengkap">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nomor Telepon / WA</label>
                                <input type="text" name="recipient_phone" value="{{ $order->recipient_phone }}" required class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900 shadow-sm" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Pengiriman</label>
                            <textarea name="shipping_address" required rows="3" class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:ring-brand-900 focus:border-brand-900 shadow-sm" placeholder="Nama Jalan, Gedung, RT/RW, Kecamatan, Kota/Kab, Kodepos...">{{ $order->shipping_address }}</textarea>
                        </div>
                    </div>

                    <template x-for="(item, idx) in items" :key="item.id">
                        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl overflow-hidden">
                            <!-- Header Item -->
                            <div class="bg-slate-50 p-6 md:p-8 flex items-center gap-6 border-b border-slate-100">
                                <div class="w-20 h-20 bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-inner shrink-0">
                                    <img :src="item.img" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-black text-xl text-slate-900 uppercase tracking-tight" x-text="item.name"></h4>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        Qty: <span class="text-brand-900" x-text="item.qty + ' Pcs'"></span>
                                        <span class="mx-2 text-slate-200">|</span>
                                        Bahan: <span class="text-brand-600" x-text="item.materialName"></span>
                                    </p>
                                </div>
                            </div>

                            <!-- Desain Input -->
                            <div class="p-6 md:p-8 border-b border-slate-100">
                                <div class="flex items-center justify-between mb-6">
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-widest">1. Manajemen Desain</h5>
                                    <div class="flex gap-2 p-1 bg-slate-100 rounded-xl">
                                        <button type="button" @click="item.designMethod = 'upload'" class="px-4 py-1.5 text-[9px] font-black uppercase rounded-lg transition-all" :class="item.designMethod === 'upload' ? 'bg-white shadow-sm text-brand-900' : 'text-slate-400'">Upload</button>
                                        <button type="button" @click="item.designMethod = 'customizer'" class="px-4 py-1.5 text-[9px] font-black uppercase rounded-lg transition-all" :class="item.designMethod === 'customizer' ? 'bg-white shadow-sm text-brand-900' : 'text-slate-400'">Web Design</button>
                                    </div>
                                </div>
                                
                                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 mb-6 flex items-center gap-6">
                                    <div class="w-24 h-24 bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm shrink-0">
                                        <img :src="item.designPreview" class="w-full h-full object-cover" x-show="item.designPreview">
                                        <div class="flex items-center justify-center h-full bg-slate-100 text-slate-300" x-show="!item.designPreview">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-900 uppercase tracking-widest">Desain Saat Ini</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1" x-text="item.designMethod === 'upload' ? 'Anda dapat mengunggah beberapa file desain sekaligus.' : 'Pilih desain yang sudah Anda buat di Customizer.'"></p>
                                    </div>
                                </div>

                                <!-- Upload Method -->
                                <div x-show="item.designMethod === 'upload'" x-transition>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Upload File Desain (.zip / .cdr / .ai / .jpg - Bisa pilih banyak)</label>
                                    <input type="file" :name="'designs['+item.id+'][files][]'" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-50 file:text-brand-900 hover:file:bg-slate-100 transition-all border border-slate-100 rounded-xl p-2 cursor-pointer">
                                </div>

                                <!-- Customizer Method -->
                                <div x-show="item.designMethod === 'customizer'" x-transition>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Pilih Desain dari Customizer</label>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 max-h-64 overflow-y-auto p-2">
                                        @foreach($userDesigns as $d)
                                            <label class="relative cursor-pointer group">
                                                <input type="radio" :name="'designs['+item.id+'][design_id]'" value="{{ $d->id }}" class="peer absolute opacity-0" @change="item.designPreview = '{{ Storage::url($d->preview_path) }}'">
                                                <div class="bg-white border-2 border-slate-100 rounded-xl p-2 group-hover:border-brand-900 peer-checked:border-brand-900 peer-checked:bg-brand-50/50 transition-all">
                                                    <div class="aspect-square bg-slate-50 rounded-lg overflow-hidden mb-2">
                                                        <img src="{{ Storage::url($d->preview_path) }}" class="w-full h-full object-cover">
                                                    </div>
                                                    <p class="text-[9px] font-black text-slate-900 uppercase truncate text-center">{{ $d->name }}</p>
                                                </div>
                                                <div class="absolute top-3 right-3 w-5 h-5 bg-brand-900 text-white rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-all">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @if(count($userDesigns) === 0)
                                        <div class="p-6 bg-slate-50 rounded-xl text-center border border-dashed border-slate-200">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada desain tersimpan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Roster Input -->
                            <div class="p-6 md:p-8">
                                <h5 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-4">2. Roster Pemain</h5>
                                
                                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50 text-[10px] uppercase tracking-widest text-slate-400 font-black">
                                                <th class="p-4 border-b border-slate-100">No.</th>
                                                <th class="p-4 border-b border-slate-100">Nama Dada/Punggung</th>
                                                <th class="p-4 border-b border-slate-100">Nomor</th>
                                                <th class="p-4 border-b border-slate-100">Ukuran</th>
                                                <th class="p-4 border-b border-slate-100 text-center">Lengan Panjang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(player, pIdx) in item.roster" :key="pIdx">
                                                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                                    <td class="p-4 text-xs font-bold text-slate-400" x-text="pIdx + 1"></td>
                                                    <td class="p-4">
                                                        <input type="text" x-model="player.name" :name="'roster['+item.id+']['+pIdx+'][name]'" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold focus:ring-brand-900 focus:border-brand-900" placeholder="Nama Pemain">
                                                    </td>
                                                    <td class="p-4">
                                                        <input type="text" x-model="player.number" :name="'roster['+item.id+']['+pIdx+'][number]'" class="w-16 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold text-center focus:ring-brand-900 focus:border-brand-900" placeholder="00">
                                                    </td>
                                                    <td class="p-4">
                                                        <select x-model="player.size" :name="'roster['+item.id+']['+pIdx+'][size]'" class="w-20 bg-white border border-slate-200 rounded-lg px-2 py-3 text-xs font-bold focus:ring-brand-900 focus:border-brand-900">
                                                            <option value="S">S</option>
                                                            <option value="M">M</option>
                                                            <option value="L">L</option>
                                                            <option value="XL">XL</option>
                                                            <option value="XXL">XXL (+5k)</option>
                                                            <option value="XXXL">3XL (+10k)</option>
                                                        </select>
                                                    </td>
                                                    <td class="p-4 text-center">
                                                        <input type="checkbox" x-model="player.isLongSleeve" :name="'roster['+item.id+']['+pIdx+'][long_sleeve]'" value="1" class="w-6 h-6 text-brand-900 border-slate-300 rounded-lg focus:ring-brand-900 cursor-pointer">
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Upgrades Input -->
                            <div class="p-6 md:p-8 border-t border-slate-100 bg-slate-50/50">
                                <h5 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-6">3. Ekstra Kustomisasi (Upgrade)</h5>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                                    @foreach($upgrades as $category => $list)
                                        <div>
                                            <p class="text-[10px] font-bold text-brand-900 uppercase tracking-[0.2em] mb-4 pb-2 border-b border-slate-200">{{ $category }}</p>
                                            <div class="space-y-3">
                                                @foreach($list as $upgrade)
                                                <label class="flex items-start gap-3 cursor-pointer group">
                                                    <div class="pt-0.5">
                                                        <input type="checkbox" x-model="item.upgrades" value="{{ $upgrade->id }}" :name="'upgrades['+item.id+'][]'" class="w-5 h-5 text-brand-900 border-slate-300 rounded focus:ring-brand-900">
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-bold text-slate-700 group-hover:text-brand-900 transition-colors">{{ $upgrade->name }}</p>
                                                        <p class="text-[10px] text-amber-600 font-black tracking-widest mt-1">+ Rp {{ number_format($upgrade->price, 0, ',', '.') }}</p>
                                                    </div>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Desktop Summary Card -->
                <div class="hidden lg:block lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-2xl sticky top-32">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 pb-4 border-b border-slate-50">Ringkasan Update</h3>
                        
                        <div class="bg-brand-50/50 rounded-3xl p-6 space-y-3 mb-8">
                            <div class="flex justify-between items-center text-slate-500 font-bold uppercase tracking-widest text-[10px]">
                                <span>Subtotal</span>
                                <span class="text-slate-900" x-text="'Rp ' + formatRupiah(subtotal)"></span>
                            </div>
                            <div class="flex justify-between items-center text-amber-600 font-bold uppercase tracking-widest text-[10px]">
                                <span>Ekstra Roster</span>
                                <span class="text-amber-600" x-text="'+ Rp ' + formatRupiah(totalSurcharges)"></span>
                            </div>
                            <div class="h-px bg-slate-200 w-full my-3"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-black text-slate-900 uppercase tracking-widest">Total Baru</span>
                                <span class="text-2xl font-black text-brand-900 tracking-tighter" x-text="'Rp ' + formatRupiah(grandTotal)"></span>
                            </div>
                        </div>

                        <button type="submit" class="w-full px-10 py-5 bg-brand-900 text-white rounded-full font-black uppercase tracking-widest text-xs text-center shadow-2xl shadow-brand-900/20 hover:bg-brand-800 active:scale-95 transition-all">
                            Simpan Perubahan
                        </button>
                        
                        <a href="{{ route('customer.orders.show', $order->order_number) }}" class="block w-full text-center mt-6 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
                            Batal & Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Payment Bar (Mobile Only) -->
            <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-100 shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
                <div class="max-w-7xl mx-auto px-4 h-20 md:h-24 flex items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Baru</span>
                        <span class="text-xl font-black text-brand-900 tracking-tighter" x-text="'Rp ' + formatRupiah(grandTotal)"></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('customer.orders.show', $order->order_number) }}" class="flex px-4 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
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
                    $designPreview = $item->design ? ($item->design->preview_path ? asset($item->design->preview_path) : (isset($item->design->design_json['file']) ? Storage::url($item->design->design_json['file']) : null)) : null;
                @endphp
                {
                    id: {{ $item->id }},
                    name: '{{ addslashes($item->package->name) }}',
                    materialName: '{{ $item->material->name }}',
                    basePrice: {{ $item->package->base_price + ($item->material?->additional_price ?? 0) }},
                    qty: {{ $item->quantity }},
                    img: '{{ $src }}',
                    designPreview: '{{ $designPreview }}',
                    designMethod: 'upload',
                    roster: @json($item->roster),
                    upgrades: @json($item->upgrades->pluck('id'))
                },
                @endforeach
            ],
            
            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            },
            
            getItemSurcharge(item) {
                let rosterSurcharge = item.roster.reduce((sum, player) => {
                    let surcharge = 0;
                    if (player.isLongSleeve) surcharge += 20000;
                    if (player.size === 'XXL') surcharge += 5000;
                    if (player.size === 'XXXL') surcharge += 10000;
                    return sum + surcharge;
                }, 0);

                let globalSurcharge = item.upgrades.reduce((sum, uId) => {
                    return sum + (this.availableUpgrades[uId] || 0);
                }, 0) * item.qty;

                return rosterSurcharge + globalSurcharge;
            },
            
            get totalSurcharges() {
                return this.items.reduce((sum, item) => sum + this.getItemSurcharge(item), 0);
            },
            
            get subtotal() {
                return this.items.reduce((sum, item) => sum + (item.basePrice * item.qty), 0);
            },
            
            get grandTotal() {
                return this.subtotal + this.totalSurcharges;
            }
        }
    }
</script>
@endsection
