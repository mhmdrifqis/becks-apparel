@extends('layouts.main')

@section('title', 'Keranjang Saya - Becks Apparel')

@section('content')
<div x-data="cartApp()" class="min-h-screen bg-slate-50 pb-32">
    <!-- Header: Pure White & Minimal -->
    <div class="bg-white border-b border-slate-100 pt-28 pb-6 md:pt-36 md:pb-10">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Keranjang <span class="text-brand-600">Saya</span>
            </h1>
            <div class="hidden md:flex items-center gap-4">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Total {{ count($cartItems) }} Produk</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if($cartItems->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-[2.5rem] p-12 md:p-24 text-center border border-slate-100 shadow-sm">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-4">Wah, Keranjangmu Kosong!</h2>
                <p class="text-slate-400 font-medium mb-10 max-w-xs mx-auto text-sm">Yuk, cari jersey keren dan mulai desain custom milikmu sendiri.</p>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-10 py-4 bg-brand-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/20">
                    Mulai Belanja
                </a>
            </div>
        @else
            <!-- Table Header (Desktop Only) -->
            <div class="hidden lg:grid grid-cols-[auto_1fr_120px_150px_150px_100px] gap-6 items-center bg-white px-8 py-4 rounded-2xl border border-slate-100 shadow-sm text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                <div class="w-6 flex justify-center">
                    <input type="checkbox" @change="toggleAll($event.target.checked)" :checked="isAllSelected" class="w-5 h-5 rounded border-slate-200 text-brand-900 focus:ring-brand-900">
                </div>
                <div class="pl-4">Produk</div>
                <div class="text-center">Harga Satuan</div>
                <div class="text-center">Kuantitas</div>
                <div class="text-center">Total Harga</div>
                <div class="text-right">Aksi</div>
            </div>

            <!-- Cart Items List -->
            <div class="space-y-4">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-visible divide-y divide-slate-50">
                    @foreach($cartItems as $item)
                    <div class="p-4 md:p-8 lg:grid lg:grid-cols-[auto_1fr_120px_150px_150px_100px] lg:gap-6 items-center flex flex-col gap-6 group relative"
                         :class="vPicker.itemId === {{ $item->id }} ? 'z-50' : 'z-10'">
                        <!-- Checkbox -->
                        <div class="absolute top-4 left-4 lg:static lg:w-6 flex lg:justify-center z-10">
                            <input type="checkbox" :value="{{ $item->id }}" x-model="selectedItems" class="w-6 h-6 lg:w-5 lg:h-5 rounded border-slate-200 text-brand-900 focus:ring-brand-900 cursor-pointer">
                        </div>

                        <!-- Product Info -->
                        <div class="flex gap-4 md:gap-6 w-full pl-8 lg:pl-4">
                            <div class="w-24 h-24 md:w-32 md:h-32 bg-slate-50 rounded-2xl md:rounded-3xl border border-slate-100 overflow-hidden shrink-0 group-hover:shadow-lg transition-all duration-500">
                                @php $imgPath = $item->package->images[0] ?? 'assets/images/placeholder.png'; $src = str_starts_with($imgPath, 'assets/') ? asset($imgPath) : Storage::url($imgPath); @endphp
                                <img src="{{ $src }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 flex flex-col py-1">
                                <h3 class="text-sm md:text-lg font-black text-slate-900 uppercase tracking-tight leading-loose mb-2">{{ $item->package->name }}</h3>
                                
                                <!-- Shopee Style Variation Trigger -->
                                <div class="relative inline-block">
                                    <button @click="openVariantPicker({{ $item->id }})" 
                                            class="group/variasi inline-flex items-center gap-2 px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg hover:border-brand-900/30 transition-all text-left">
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest whitespace-nowrap">Bahan: <span class="text-slate-900" x-text="getItemMaterialName({{ $item->id }})"></span></span>
                                        <svg class="w-3 h-3 text-slate-400 group-hover/variasi:text-brand-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                    </button>

                                    <!-- Shopee Style Variation Popover -->
                                    <div x-show="vPicker.open && vPicker.itemId === {{ $item->id }}" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         @click.away="vPicker.open = false"
                                         class="absolute z-50 mt-2 w-[280px] md:w-[480px] bg-white border border-slate-100 shadow-[0_10px_40px_rgba(0,0,0,0.15)] rounded-2xl p-4 before:content-[''] before:absolute before:-top-2 before:left-6 before:border-l-8 before:border-l-transparent before:border-r-8 before:border-r-transparent before:border-b-8 before:border-b-white">
                                        
                                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pilih Bahan</h4>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
                                            @foreach($materials as $material)
                                            <button @click="vPicker.tempMatId = {{ $material->id }}" 
                                                    class="relative px-2 py-2.5 text-[9px] font-bold uppercase border-2 rounded-xl transition-all text-center"
                                                    :class="vPicker.tempMatId === {{ $material->id }} ? 'border-brand-900 bg-brand-50/30 text-brand-900' : 'border-slate-100 text-slate-600 hover:border-brand-900/10'">
                                                {{ $material->name }}
                                                <template x-if="vPicker.tempMatId === {{ $material->id }}">
                                                    <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-brand-900 rounded-tl-lg rounded-br-lg flex items-center justify-center">
                                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                </template>
                                            </button>
                                            @endforeach
                                        </div>

                                        <div class="flex items-center gap-2 pt-2">
                                            <button @click="vPicker.open = false" class="flex-1 py-2.5 text-[10px] font-black uppercase text-slate-400 hover:text-slate-900 transition-colors">Nanti Saja</button>
                                            <button @click="confirmVariant({{ $item->id }})" class="flex-1 py-2.5 bg-brand-900 text-white text-[10px] font-black uppercase rounded-xl shadow-lg shadow-brand-900/20 active:scale-95 transition-all">Konfirmasi</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile Price and Qty -->
                                <div class="mt-4 flex items-center justify-between lg:hidden">
                                    <span class="text-sm font-black text-brand-900" x-text="formatPrice(getItemPrice({{ $item->id }}))"></span>
                                    <div class="flex items-center gap-1 p-1 bg-slate-50 rounded-xl border border-slate-100">
                                        <button @click="changeQty({{ $item->id }}, -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-400 hover:text-brand-900 border border-slate-100">-</button>
                                        <input type="number" class="w-10 text-center bg-transparent border-none text-[11px] font-black p-0 focus:ring-0" x-model="items.find(i => i.id === {{ $item->id }}).qty" readonly>
                                        <button @click="changeQty({{ $item->id }}, 1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-400 hover:text-brand-900 border border-slate-100">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit Price (Desktop Only) -->
                        <div class="hidden lg:block text-center text-sm font-bold text-slate-800" x-text="formatPrice(getItemPrice({{ $item->id }}))"></div>

                        <!-- Quantity (Desktop Only) -->
                        <div class="hidden lg:flex justify-center items-center">
                            <div class="flex items-center gap-1 p-1 bg-slate-50 rounded-xl border border-slate-100">
                                <button @click="changeQty({{ $item->id }}, -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-400 hover:text-brand-900 border border-slate-100 transition-colors">-</button>
                                <input type="number" class="w-10 text-center bg-transparent border-none text-xs font-black p-0 focus:ring-0" x-model="items.find(i => i.id === {{ $item->id }}).qty" readonly>
                                <button @click="changeQty({{ $item->id }}, 1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-400 hover:text-brand-900 border border-slate-100 transition-colors">+</button>
                            </div>
                        </div>

                        <!-- Subtotal (Desktop Only) -->
                        <div class="hidden lg:block text-center text-sm font-black text-brand-900" x-text="formatPrice(getItemPrice({{ $item->id }}) * items.find(i => i.id === {{ $item->id }}).qty)"></div>

                        <!-- Actions -->
                        <div class="w-full lg:w-auto flex justify-end">
                            <button @click="removeItem({{ $item->id }})" class="text-xs font-black text-rose-500 uppercase tracking-widest hover:text-rose-600 transition-colors px-4 py-2 hover:bg-rose-50 rounded-xl">Hapus</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Sticky Bottom Summary Bar -->
            <div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-100 shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
                <div class="max-w-7xl mx-auto px-4 md:px-8 h-20 md:h-24 flex items-center justify-between gap-4">
                    <!-- Left: Select All -->
                    <div class="flex items-center gap-3">
                        <input type="checkbox" @change="toggleAll($event.target.checked)" :checked="isAllSelected" class="w-6 h-6 md:w-5 md:h-5 rounded border-slate-200 text-brand-900 focus:ring-brand-900 cursor-pointer">
                        <span class="text-[10px] md:text-xs font-black text-slate-900 uppercase tracking-widest leading-none">Pilih Semua<br class="md:hidden"/>(<span x-text="selectedItems.length"></span>)</span>
                    </div>

                    <!-- Right: Total & Checkout -->
                    <div class="flex items-center gap-4 md:gap-10">
                        <div class="text-right">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Total Estimasi</span>
                            <span class="text-xl md:text-3xl font-black text-brand-900 tracking-tighter" x-text="formatPrice(total)"></span>
                        </div>
                        <button @click="proceedToCheckout()" 
                                class="h-12 md:h-16 px-6 md:px-12 bg-brand-900 text-white rounded-[1rem] md:rounded-2xl font-black uppercase tracking-widest md:tracking-[0.2em] text-[10px] md:text-xs hover:bg-brand-800 active:scale-95 transition-all shadow-xl shadow-brand-900/20 disabled:opacity-50 disabled:cursor-not-allowed" 
                                :disabled="selectedItems.length === 0">
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function cartApp() {
        return {
            items: @js($cartItems->map(fn($i) => [
                'id' => $i->id,
                'package_id' => $i->package_id,
                'name' => $i->package->name,
                'qty' => $i->quantity,
                'price' => (float)$i->package->base_price, // Ensure number
                'mat_id' => $i->material_id,
                'mat_name' => $i->material->name ?? '-',
                'mat_price' => (float) ($i->material->additional_price ?? 0)
            ])),
            materials: @js($materials),
            selectedItems: [],
            
            // Variation Picker state
            vPicker: {
                open: false,
                itemId: null,
                tempMatId: null
            },
            
            init() {
                // Auto-select from URL parameter
                const urlParams = new URLSearchParams(window.location.search);
                const selectId = urlParams.get('select');
                if (selectId) {
                    this.selectedItems.push(parseInt(selectId));
                }
            },

            get isAllSelected() {
                return this.selectedItems.length === this.items.length && this.items.length > 0;
            },

            toggleAll(checked) {
                if (checked) {
                    this.selectedItems = this.items.map(i => i.id);
                } else {
                    this.selectedItems = [];
                }
            },

            getItemPrice(id) {
                let it = this.items.find(i => i.id === id);
                return (parseFloat(it.price) + parseFloat(it.mat_price));
            },

            getItemMaterialName(id) {
                let it = this.items.find(i => i.id === id);
                return it.mat_name;
            },

            getItemMaterialId(id) {
                let it = this.items.find(i => i.id === id);
                return it.mat_id;
            },

            formatPrice(price) {
                return 'Rp' + new Intl.NumberFormat('id-ID').format(price);
            },

            get total() {
                return this.items
                    .filter(i => this.selectedItems.map(Number).includes(Number(i.id)))
                    .reduce((sum, i) => {
                        const basePrice = parseFloat(i.price) || 0;
                        const matPrice = parseFloat(i.mat_price) || 0;
                        return sum + ((basePrice + matPrice) * i.qty);
                    }, 0);
            },

            async changeQty(id, delta) {
                let it = this.items.find(i => i.id === id);
                if (!it) return;
                
                let newQty = it.qty + delta;
                if (newQty < 1) return;

                // Optimistic UI
                const oldQty = it.qty;
                it.qty = newQty;

                try {
                    const formData = new FormData();
                    formData.append('_method', 'PATCH');
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('quantity', newQty);

                    let res = await fetch(`{{ url('/cart') }}/${id}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const text = await res.text();
                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}: ${text.substring(0, 50)}`);
                    }

                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch(e) {
                        throw new Error('Server invalid JSON');
                    }

                    if (!data.success) {
                        it.qty = oldQty;
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal: ' + (data.message || 'Error server'), type: 'error' } }));
                    } else {
                         this.$dispatch('cart-updated');
                    }
                } catch (e) {
                    it.qty = oldQty;
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Error: ' + e.message, type: 'error' } }));
                }
            },

            openVariantPicker(itemId) {
                let it = this.items.find(i => i.id === itemId);
                this.vPicker.itemId = itemId;
                this.vPicker.tempMatId = it.mat_id;
                this.vPicker.open = true;
            },

            async confirmVariant(itemId) {
                let it = this.items.find(i => i.id === itemId);
                
                try {
                    const formData = new FormData();
                    formData.append('_method', 'PATCH');
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('material_id', this.vPicker.tempMatId);

                    let res = await fetch(`{{ url('/cart') }}/${itemId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    const text = await res.text();
                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}`);
                    }

                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch(e) {
                        throw new Error('Server invalid response');
                    }

                    if (data.success) {
                        it.mat_id = data.material_id;
                        it.mat_name = data.material_name;
                        it.mat_price = parseFloat(data.price) - parseFloat(it.price);
                        
                        this.vPicker.open = false;
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Bahan berhasil diubah', type: 'success' } }));
                    } else {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal ganti bahan', type: 'error' } }));
                    }
                } catch (e) {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Error: ' + e.message, type: 'error' } }));
                }
            },

            async removeItem(id) {
                if (!confirm('Hapus item dari keranjang?')) return;
                
                try {
                    let res = await fetch(`{{ url('/cart') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    // Simple reload for simplicity on delete
                    window.location.reload();
                } catch (e) {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal hapus item', type: 'error' } }));
                }
            },

            proceedToCheckout() {
                if (this.selectedItems.length === 0) return;
                let url = new URL('{{ route('customer.checkout.index') }}', window.location.origin);
                this.selectedItems.forEach(id => url.searchParams.append('items[]', id));
                window.location.href = url.toString();
            }
        }
    }
</script>
@endsection
