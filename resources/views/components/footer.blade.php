<footer class="bg-white dark:bg-zinc-950 border-t dark:border-zinc-900 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-2">
                <a href="{{ url('/') }}" class="flex items-center gap-2 mb-6 text-decoration-none">
                    <x-application-logo class="h-8 w-auto fill-current text-brand-900 dark:text-brand-400" />
                    <span class="font-bold text-2xl tracking-tighter">BECKS<span class="text-brand-600 dark:text-brand-400">APPAREL</span></span>
                </a>
                <p class="text-gray-600 dark:text-gray-400 max-w-sm mb-6 leading-relaxed">
                    Penyedia jersey olahraga premium dengan fitur kustomisasi interaktif. Kami mengutamakan kualitas bahan dan kepuasan pelanggan di setiap jahitan.
                </p>
                <div class="flex gap-4">
                    <div class="h-10 w-10 bg-gray-100 dark:bg-zinc-900 rounded-full flex items-center justify-center hover:bg-brand-900 hover:text-white transition-all cursor-pointer">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Navigasi</h4>
                <ul class="space-y-4 text-gray-600 dark:text-gray-400">
                    <li><a href="#" class="hover:text-brand-600 transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-brand-600 transition-colors">Katalog Produk</a></li>
                    <li><a href="#" class="hover:text-brand-600 transition-colors">Cara Order</a></li>
                    <li><a href="#" class="hover:text-brand-600 transition-colors">Testimoni</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Pembayaran</h4>
                <div class="p-4 bg-gray-50 dark:bg-zinc-900 rounded-lg border dark:border-zinc-800">
                    <p class="text-xs text-gray-500 uppercase font-bold mb-2">Transfer Bank Mandiri</p>
                    <p class="text-sm font-bold text-brand-900 dark:text-brand-400 mb-1">102-00-1294776-5</p>
                    <p class="text-xs dark:text-zinc-500 font-medium">a.n. PT BOLA MEDIA SPORTAINMENT</p>
                </div>
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
