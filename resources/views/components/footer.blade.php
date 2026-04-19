<footer class="bg-white dark:bg-zinc-950 border-t dark:border-zinc-900 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-12 mb-16">
            <div class="col-span-1 md:col-span-2">
                <a href="{{ url('/') }}" class="flex items-center gap-2 mb-6 text-decoration-none">
                    <x-application-logo class="h-8 w-auto fill-current text-brand-900 dark:text-brand-400" />
                    <span class="font-bold text-2xl tracking-tighter uppercase">BECKS<span class="text-brand-600 dark:text-brand-400">APPAREL</span></span>
                </a>
                <h3 class="text-xl font-black text-brand-600 dark:text-brand-400 italic uppercase tracking-[0.2em] mb-4">#WEARYOURPRIDE</h3>
                <p class="text-gray-600 dark:text-gray-400 max-w-sm mb-8 leading-relaxed font-medium">
                    Penyedia jersey olahraga premium dengan fitur kustomisasi interaktif. Kami mengutamakan kualitas bahan dan kepuasan pelanggan di setiap jahitan.
                </p>
            </div>

            <div>
                <h4 class="font-black text-xs uppercase tracking-[0.2em] text-brand-900 dark:text-brand-400 mb-8">Navigasi</h4>
                <ul class="space-y-4 text-sm font-bold text-gray-600 dark:text-gray-400">
                    <li><a href="#" class="hover:text-brand-600 transition-colors uppercase tracking-widest">Tentang Kami</a></li>
                    <li><a href="{{ route('visi-misi') }}" class="hover:text-brand-600 transition-colors uppercase tracking-widest">Visi & Misi</a></li>
                    <li><a href="{{ route('portfolio') }}" class="hover:text-brand-600 transition-colors uppercase tracking-widest">Portfolio</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-brand-600 transition-colors uppercase tracking-widest">Katalog</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-black text-xs uppercase tracking-[0.2em] text-brand-900 dark:text-brand-400 mb-8">Store</h4>
                <ul class="space-y-4 text-sm font-bold text-gray-600 dark:text-gray-400">
                    <li>
                        <a href="https://maps.google.com/?q=Becks+Apparel" target="_blank" class="flex items-center gap-2 hover:text-brand-600 transition-colors uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Google Maps
                        </a>
                    </li>
                    <li class="text-xs text-gray-400 leading-relaxed font-medium mt-2">
                        Hubungi kami untuk janji temu atau kunjungan langsung ke workshop produksi Becks Apparel.
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-black text-xs uppercase tracking-[0.2em] text-brand-900 dark:text-brand-400 mb-8">Social Media</h4>
                <ul class="space-y-4">
                    <li>
                        <a href="https://www.instagram.com/becks_apparel/" target="_blank" class="flex items-center gap-3 group">
                            <div class="h-10 w-10 bg-gray-100 dark:bg-zinc-900 rounded-2xl flex items-center justify-center group-hover:bg-brand-950 group-hover:text-white transition-all border dark:border-zinc-800">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest leading-none mb-1">Follow Us</p>
                                <p class="text-sm font-black text-gray-900 dark:text-brand-100 uppercase tracking-tighter">@becks_apparel</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="https://wa.me/6285183327132" target="_blank" class="flex items-center gap-3 group">
                            <div class="h-10 w-10 bg-gray-100 dark:bg-zinc-900 rounded-2xl flex items-center justify-center group-hover:bg-brand-950 group-hover:text-white transition-all border dark:border-zinc-800">
                                <svg class="w-5 h-5" fill="currentColor" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>WhatsApp</title><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest leading-none mb-1">WhatsApp Hub</p>
                                <p class="text-sm font-black text-gray-900 dark:text-brand-100 uppercase tracking-tighter">0851-8332-7132</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="pt-8 border-t dark:border-zinc-900 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
            <p>© {{ date('Y') }} Becks Apparel. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-brand-600 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-brand-600 transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
