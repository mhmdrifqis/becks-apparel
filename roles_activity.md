# ROLES & ACTIVITY DIAGRAM - BECKS APPAREL

## 1. PELANGGAN (CUSTOMER)
**Menu Utama:**
* **Dashboard Pelanggan**: Ringkasan pesanan aktif dan profil pengguna.
* **Katalog & Customizer**: Antarmuka interaktif untuk mendesain jersey (pilih model, warna, font, logo).
* **Keranjang & Checkout**: Manajemen item sebelum pembayaran dan integrasi QRIS.
* **Riwayat & Tracking**: Daftar transaksi, unduh invoice PDF, dan pelacakan status produksi/pengiriman.
* **Bantuan (Chatbot AI)**: Konsultasi desain dan bantuan operasional secara real-time.
* **Pengajuan Retur**: Formulir klaim jika barang tidak sesuai.

**Alur Aktivitas:**
1. Login/Register (via Google Socialite atau Email).
2. Memilih produk dari katalog dan masuk ke mode Kustomisasi.
3. Membuat desain (Fabric.js), sistem menyimpan metadata JSON.
4. Checkout dan melakukan pembayaran otomatis (Midtrans QRIS).
5. Menerima invoice otomatis via WhatsApp (Fonnte).
6. Memantau progres produksi di dashboard.
7. Menerima barang dan melakukan pelacakan resi.
8. Mengajukan retur jika ditemukan cacat produksi.

---

## 2. ADMIN
**Menu Utama:**
* **Dashboard Admin**: Metrik pesanan masuk, total pendapatan, dan log sistem.
* **Manajemen Produk**: CRUD katalog jersey, jaket, tshirt, kemeja, dan harga.
* **Manajemen Transaksi**: Kontrol status pesanan dan verifikasi pembayaran.
* **Manajemen Inventaris**: Monitoring stok bahan baku (kain, tinta) dan barang jadi.
* **Manajemen Pengiriman**: Input nomor resi kurir dan update status kirim.
* **Manajemen Retur**: Validasi dan approval pengajuan retur pelanggan.
* **Laporan Center**: Ekspor data penjualan ke format PDF/Excel.

**Alur Aktivitas:**
1. Login ke Dashboard Filament.
2. Mengelola data master produk dan ketersediaan stok.
3. Memantau pesanan masuk dan memastikan pembayaran sukses.
4. Memvalidasi ketersediaan bahan untuk diteruskan ke Tim Produksi.
5. Menginput nomor resi setelah produksi selesai ditandai "Siap Kirim".
6. Menyetujui/menolak pengajuan retur berdasarkan bukti foto.

---

## 3. TIM PRODUKSI
**Menu Utama:**
* **Dashboard Antrean Produksi**: Daftar pesanan berstatus "Paid" yang siap dikerjakan.
* **Detail Produksi**: Akses file desain teknis (JSON) dan spesifikasi bahan.
* **Update Status Tahapan**: Tombol perubahan status (Antrean Cetak -> Jahit -> QC -> Siap Kirim).
* **Kontrol Bahan Baku**: Pencatatan pemakaian bahan per pesanan untuk update stok otomatis.

**Alur Aktivitas:**
1. Login ke Dashboard khusus Produksi.
2. Melihat daftar antrean pesanan yang sudah lunas.
3. Membuka detail pesanan untuk melihat desain JSON dari Fabric.js.
4. Memulai produksi dan memperbarui status tahapan secara berkala.
5. Menginput jumlah bahan yang terpakai untuk pesanan tersebut.
6. Menandai pesanan sebagai "Siap Kirim" untuk di-handle oleh Admin.

---

## 4. MANAJEMEN / OWNER
**Menu Utama:**
* **Executive Analytics**: Grafik performa bisnis, tren produk, dan pendapatan periode.
* **Monitoring Kinerja**: Evaluasi kecepatan produksi dan aktivitas admin.
* **Financial Reporting**: Akses laporan laba-rugi dan akumulasi transaksi.

**Alur Aktivitas:**
1. Login ke Dashboard Panel Owner.
2. Meninjau analitik penjualan untuk pengambilan keputusan strategis.
3. Memastikan alur stok dan keuangan berjalan sesuai target.
4. Mengunduh laporan bulanan untuk arsip perusahaan.
