# BECKS APPAREL - PROJECT BLUEPRINT

## 1. Visi & Konteks Bisnis
Aplikasi Web E-Commerce dengan Fitur Kustomisasi Jersey Interaktif untuk Becks Apparel di bawah naungan PT Bola Media Sportainment. Fokus utama adalah digitalisasi proses pemesanan, kustomisasi desain mandiri, dan transparansi alur produksi.

## 2. Aktor Sistem (4 Pilar)
* **Pelanggan**: Melakukan kustomisasi desain, checkout, pembayaran QRIS, dan tracking pesanan.
* **Admin**: Mengelola katalog, inventaris stok, verifikasi transaksi, dan manajemen retur.
* **Tim Produksi**: Memproses antrean pesanan "Paid", mengakses desain JSON, dan update status produksi.
* **Manajemen/Owner**: Memantau laporan analitik harian/bulanan dan performa bisnis secara keseluruhan.

## 3. Aturan Harga & Produk (Berdasarkan Katalog Terbaru)
Sistem harus menggunakan logika harga sesuai katalog resmi 
* **Paket Jersey (Stelan)**:
    * Paket A: Rp 90.000 (Non-printing, Logo/Nameset DTF).
    * Paket B: Rp 110.000 (Lengan Printing, Logo/Nameset DTF).
    * Paket C: Rp 130.000 (Jersey Full Printing, Celana Non-printing).
    * Paket D: Rp 160.000 (Jersey & Celana Full Printing).
    * Paket E: Rp 170.000 (Full Printing + Logo/Sponsor DTF).
* **Jaket (Jacket)**:
    * Paket A: Rp 170.000 (Jacket Full Printing).
    * Paket B: Rp 155.000 (Kombinasi Printing + Bahan).
    * Paket C: Rp 250.000 (Setelan Jaket + Celana Training).
* **Kaos (Tshirt) & Kemeja**:
    * Kaos 24s: Rp 80.000.
    * Kaos 30s: Rp 60.000.
    * Kemeja: Mulai dari Rp 80.000.
* **Upgrade & Opsi Tambahan**:
    * Logo Rubber (+20k), Logo Semiwoven (+25k), Logo Bordir (+30k).
    * Tangan Panjang (+20k), Kerah Kemeja (+20k), Tangan Raglan (+15k).
    * Kaos Kaki Custom (+35k), Rompi Custom (+25k), Flag Custom (+20k).
* **Logika Surcharge Ukuran (>XL)**:
    * Jersey: Kelipatan +Rp 5.000 per level size di atas XL.
    * Kaos/Kemeja: Kelipatan +Rp 10.000 per level size di atas XL.

## 4. Aturan Bisnis Penting
* **Pembayaran**: Hanya melalui Bank Mandiri 102-00-1294776-5 a.n. PT BOLA MEDIA SPORTAINMENT.
* **Status Produksi**: Urutan status adalah Paid -> Antrean Cetak -> Proses Jahit -> Quality Control -> Siap Kirim.
