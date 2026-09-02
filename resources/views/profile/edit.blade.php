@extends('layouts.main')

@section('title', 'Pengaturan Akun - Becks Apparel')

@section('content')
    <div class="min-h-screen bg-slate-50" x-data="profileSettings()">
        <!-- Header: Pure White & Minimal -->
        <div class="bg-white border-b border-slate-100 pt-28 pb-0 md:pt-36">
            <div class="max-w-7xl mx-auto px-4">
                <div class="mb-6 md:mb-8">
                    <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3 mb-2">
                        <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pengaturan <span class="text-brand-600">Akun</span>
                    </h1>
                    <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">
                        Kelola informasi profil, keamanan, dan alamat pengiriman Anda.
                    </p>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex items-center gap-8 overflow-x-auto no-scrollbar">
                    <button 
                        @click="activeTab = 'profil'"
                        :class="activeTab === 'profil' ? 'border-brand-900 text-brand-900' : 'border-transparent text-slate-400 hover:text-slate-600'"
                        class="pb-4 text-[10px] md:text-xs font-black uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap"
                    >
                        Profil & Keamanan
                    </button>
                    <button 
                        @click="activeTab = 'alamat'"
                        :class="activeTab === 'alamat' ? 'border-brand-900 text-brand-900' : 'border-transparent text-slate-400 hover:text-slate-600'"
                        class="pb-4 text-[10px] md:text-xs font-black uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap"
                    >
                        Daftar Alamat
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
            <!-- Content Area -->
            <div class="animate-scale-up">
                
                <!-- TAB 1: Profil -->
                <div x-show="activeTab === 'profil'" class="space-y-8" x-cloak>
                    <!-- Update Profile Info -->
                    <div class="p-6 md:p-10 bg-white rounded-[2rem] shadow-xl border border-gray-100">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="p-6 md:p-10 bg-white rounded-[2rem] shadow-xl border border-gray-100">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="p-6 md:p-10 bg-white rounded-[2rem] shadow-xl border border-gray-100 border-red-100">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Daftar Alamat -->
                <div x-show="activeTab === 'alamat'" class="space-y-6" x-cloak>
                    
                    <div class="flex justify-between items-center bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                        <div>
                            <h2 class="text-xl font-black uppercase tracking-widest text-brand-900">Alamat Tersimpan</h2>
                            <p class="text-sm text-gray-500 font-medium mt-1">Kelola daftar alamat pengiriman untuk mempermudah checkout.</p>
                        </div>
                        <button @click="openAddressModal()" class="px-5 py-2.5 bg-brand-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-brand-800 shadow-lg shadow-brand-900/20 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Tambah Alamat
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div x-show="isLoading" class="p-10 text-center text-gray-500 font-bold uppercase tracking-widest text-sm animate-pulse">
                        Memuat Alamat...
                    </div>

                    <!-- Address List Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="!isLoading">
                        <template x-if="addresses.length === 0">
                            <div class="col-span-full p-12 text-center bg-white rounded-[2rem] border border-gray-100 border-dashed">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="text-gray-500 font-medium">Belum ada alamat yang tersimpan.</p>
                            </div>
                        </template>

                        <template x-for="addr in addresses" :key="addr.id">
                            <div class="p-6 bg-white rounded-[1.5rem] shadow-sm border transition-all" :class="addr.is_default ? 'border-brand-500 ring-2 ring-brand-100' : 'border-gray-100 hover:border-brand-200'">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-slate-100 text-slate-700" x-text="addr.label || 'Alamat'"></span>
                                        <template x-if="addr.is_default">
                                            <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-brand-100 text-brand-700 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Utama
                                            </span>
                                        </template>
                                    </div>
                                </div>
                                <h3 class="font-bold text-gray-900" x-text="addr.nama_penerima"></h3>
                                <p class="text-sm font-medium text-gray-500 mb-2" x-text="addr.no_telepon"></p>
                                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                    <span x-text="addr.alamat_lengkap"></span><br>
                                    <span x-text="addr.kota + ', ' + addr.provinsi"></span><br>
                                    <span x-text="'Kode Pos: ' + addr.kode_pos"></span>
                                </p>
                                
                                <div class="flex items-center gap-3 pt-4 border-t border-gray-50">
                                    <template x-if="!addr.is_default">
                                        <button @click="setDefault(addr.id)" class="text-xs font-bold text-brand-600 hover:text-brand-800 transition-colors uppercase tracking-widest">
                                            Jadikan Utama
                                        </button>
                                    </template>
                                    <div class="flex-1"></div>
                                    <button @click="deleteAddress(addr.id)" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Modal -->
        <div x-show="showAddressModal" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showAddressModal = false" x-transition.opacity></div>
            
            <div class="relative bg-white w-full max-w-2xl m-4 rounded-[2rem] shadow-2xl overflow-hidden" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-black text-slate-900 uppercase tracking-widest">Tambah Alamat Baru</h3>
                    <button @click="showAddressModal = false" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Label Alamat <span class="text-slate-400">(Opsional)</span></label>
                                <input type="text" x-model="form.label" placeholder="Cth: Rumah, Kantor" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Nama Penerima</label>
                                <input type="text" x-model="form.nama_penerima" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">No. Telepon / WA</label>
                            <input type="text" x-model="form.no_telepon" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Provinsi</label>
                            <select x-model="form.rajaongkir_province_id" @change="fetchCities(form.rajaongkir_province_id)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow">
                                <option value="">Pilih Provinsi...</option>
                                <template x-for="prov in provinces" :key="prov.province_id">
                                    <option :value="prov.province_id" x-text="prov.province"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kota / Kabupaten</label>
                            <select x-model="form.rajaongkir_city_id" :disabled="!form.rajaongkir_province_id || isLoadingCities" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="" x-text="isLoadingCities ? 'Memuat Kota...' : 'Pilih Kota...'"></option>
                                <template x-for="city in cities" :key="city.city_id">
                                    <option :value="city.city_id" x-text="(city.type == 'Kota' ? 'Kota ' : 'Kab. ') + city.city_name"></option>
                                </template>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                                <textarea x-model="form.alamat_lengkap" rows="3" placeholder="Nama jalan, gedung, no. rumah, RT/RW..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kode Pos</label>
                                <input type="text" x-model="form.kode_pos" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow">
                            </div>
                        </div>

                        <div x-show="formError" class="p-3 bg-red-50 text-red-600 text-sm font-medium rounded-xl border border-red-100" x-text="formError"></div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                    <button @click="showAddressModal = false" class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                    <button @click="saveAddress()" :disabled="isSaving" class="px-6 py-2.5 bg-brand-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-brand-800 shadow-lg shadow-brand-900/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <span x-show="!isSaving">Simpan Alamat</span>
                        <span x-show="isSaving">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('profileSettings', () => ({
            activeTab: 'profil',
            addresses: [],
            isLoading: false,
            
            // Modal state
            showAddressModal: false,
            provinces: [],
            cities: [],
            isLoadingCities: false,
            isSaving: false,
            formError: null,
            
            form: {
                label: '',
                nama_penerima: '{{ Auth::user()->name }}',
                no_telepon: '',
                alamat_lengkap: '',
                kode_pos: '',
                rajaongkir_province_id: '',
                rajaongkir_city_id: '',
                provinsi: '',
                kota: ''
            },

            init() {
                // Determine active tab from URL hash if exists
                if (window.location.hash === '#alamat') {
                    this.activeTab = 'alamat';
                }
                
                this.fetchAddresses();
                this.fetchProvinces();
            },

            async fetchAddresses() {
                this.isLoading = true;
                try {
                    let res = await fetch('{{ route("user.addresses.index") }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        this.addresses = await res.json();
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.isLoading = false;
                }
            },

            async fetchProvinces() {
                try {
                    let res = await fetch('{{ route("shipping.provinces") }}');
                    let data = await res.json();
                    this.provinces = data.rajaongkir?.results || [];
                } catch (e) {
                    console.error("Failed to load provinces", e);
                }
            },

            async fetchCities(provinceId) {
                this.cities = [];
                this.form.rajaongkir_city_id = '';
                if (!provinceId) return;
                
                this.isLoadingCities = true;
                try {
                    let res = await fetch(`{{ url('/shipping/cities') }}/${provinceId}`);
                    let data = await res.json();
                    this.cities = data.rajaongkir?.results || [];
                } catch (e) {
                    console.error("Failed to load cities", e);
                } finally {
                    this.isLoadingCities = false;
                }
            },

            openAddressModal() {
                this.form = {
                    label: '',
                    nama_penerima: '{{ Auth::user()->name }}',
                    no_telepon: '',
                    alamat_lengkap: '',
                    kode_pos: '',
                    rajaongkir_province_id: '',
                    rajaongkir_city_id: '',
                    provinsi: '',
                    kota: ''
                };
                this.formError = null;
                this.showAddressModal = true;
            },

            async saveAddress() {
                this.formError = null;
                
                // Validate basic fields
                if (!this.form.nama_penerima || !this.form.no_telepon || !this.form.alamat_lengkap || !this.form.rajaongkir_province_id || !this.form.rajaongkir_city_id) {
                    this.formError = 'Harap isi semua kolom wajib (Nama, Telepon, Provinsi, Kota, Alamat).';
                    return;
                }

                // Get Province and City names
                let prov = this.provinces.find(p => p.province_id == this.form.rajaongkir_province_id);
                let city = this.cities.find(c => c.city_id == this.form.rajaongkir_city_id);
                
                if (prov) this.form.provinsi = prov.province;
                if (city) this.form.kota = (city.type == 'Kota' ? 'Kota ' : 'Kab. ') + city.city_name;

                this.isSaving = true;
                try {
                    let res = await fetch('{{ route("user.addresses.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.form)
                    });

                    let data = await res.json();
                    if (res.ok && data.success) {
                        this.addresses = data.addresses;
                        this.showAddressModal = false;
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.message, type: 'success' } }));
                    } else {
                        this.formError = data.message || 'Terjadi kesalahan saat menyimpan alamat.';
                    }
                } catch (e) {
                    this.formError = 'Terjadi kesalahan jaringan.';
                    console.error(e);
                } finally {
                    this.isSaving = false;
                }
            },

            async setDefault(id) {
                try {
                    let res = await fetch(`{{ url('/user/addresses') }}/${id}/set-default`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    let data = await res.json();
                    if (res.ok && data.success) {
                        this.addresses = data.addresses;
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.message, type: 'success' } }));
                    }
                } catch (e) {
                    console.error(e);
                }
            },

            async deleteAddress(id) {
                if (!confirm('Apakah Anda yakin ingin menghapus alamat ini?')) return;
                
                try {
                    let res = await fetch(`{{ url('/user/addresses') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    let data = await res.json();
                    if (res.ok && data.success) {
                        this.addresses = data.addresses;
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.message, type: 'success' } }));
                    }
                } catch (e) {
                    console.error(e);
                }
            }
        }));
    });
</script>
@endpush
