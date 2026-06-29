@extends('layouts.main')

@section('title', 'Koleksi Desain Saya - Becks Apparel')

@section('content')
    <div class="min-h-screen bg-slate-50">
        <!-- Header: Pure White & Minimal -->
        <div class="bg-white border-b border-slate-100 pt-28 pb-6 md:pt-36 md:pb-10">
            <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
                <h1 class="text-xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter flex items-center gap-3">
                    <svg class="w-8 h-8 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Desain <span class="text-brand-600">Saya</span>
                </h1>
                <div class="flex items-center gap-4">
                    <span class="hidden md:inline-block text-xs font-black text-slate-400 uppercase tracking-widest">Total {{ count($designs ?? []) }} Desain</span>
                    <a href="{{ route('customizer') }}" class="flex items-center gap-2 bg-brand-900 hover:bg-brand-800 text-white px-4 md:px-6 py-2 md:py-2.5 rounded-full font-bold text-xs md:text-sm uppercase tracking-widest transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        <span class="hidden sm:inline">Buat Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">

            <!-- Content -->
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden min-h-[400px]">
                @if(isset($designs) && count($designs) > 0)
                    <!-- UI for when designs exist -->
                    <div class="p-8 md:p-12">
                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                             @foreach($designs as $design)
                                 <div class="bg-slate-50 rounded-3xl border border-slate-100 overflow-hidden group hover:shadow-2xl hover:shadow-brand-900/10 transition-all duration-500">
                                     <div class="aspect-square relative bg-white flex items-center justify-center p-6">
                                         @if($design->preview_path)
                                             <img src="{{ route('images.designs', basename($design->preview_path)) }}" alt="{{ $design->name }}" class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-700">
                                         @else
                                             <div class="w-full h-full bg-slate-100 flex items-center justify-center rounded-2xl">
                                                 <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                             </div>
                                         @endif
                                         <div class="absolute inset-0 bg-brand-900/0 group-hover:bg-brand-900/5 transition-colors duration-500"></div>
                                     </div>
                                     <div class="p-6">
                                         <h3 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-brand-600 transition-colors">{{ $design->name }}</h3>
                                         <p class="text-xs text-slate-400 font-medium uppercase tracking-widest mb-6">Disimpan {{ $design->created_at->diffForHumans() }}</p>
                                         
                                         <div class="flex items-center gap-3">
                                             <a href="{{ route('customer.designs.edit', $design) }}" class="flex-1 bg-white border border-slate-200 text-slate-700 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition-all text-center">Edit Design</a>
                                             <form action="{{ route('customer.designs.destroy', $design) }}" method="POST" id="delete-form-{{ $design->id }}" class="inline">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="button" onclick="confirmDelete({{ $design->id }})" class="p-3 bg-rose-50 text-rose-600 rounded-2xl hover:bg-rose-100 transition-all" title="Hapus">
                                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                 </button>
                                             </form>
                                         </div>
                                     </div>
                                 </div>
                             @endforeach
                         </div>
                    </div>
                @else
                    <!-- Empty State (Matches Orders Theme) -->
                    <div class="p-8 md:p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
                        <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-full bg-brand-50 text-brand-900">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Desain</h2>
                        <p class="text-gray-500 mb-8 max-w-sm mx-auto">
                            Anda belum memiliki koleksi desain yang disimpan. Buat desain impian Anda di Customizer sekarang!
                        </p>
                        <a href="{{ route('customizer') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand-900 text-white rounded-full font-black uppercase tracking-widest text-sm hover:bg-brand-800 transition-all shadow-lg active:scale-95">
                            Mulai Desain Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Desain?',
                text: "Desain yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#18181b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#09090b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#18181b',
                customClass: {
                    popup: 'rounded-2xl border border-gray-200',
                    confirmButton: 'rounded-full px-6 py-2 text-sm font-bold',
                    cancelButton: 'rounded-full px-6 py-2 text-sm font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus desain ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    }
</script>
@endpush
