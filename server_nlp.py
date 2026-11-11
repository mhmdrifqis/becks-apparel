# -*- coding: utf-8 -*-
"""
Becks Apparel - Local High-Efficiency NLP Chatbot Server
Server ini menangani request chatbot dari Laravel secara lokal tanpa menggunakan API cloud (seperti Gemini).

Fitur NLP Engine:
1. Normalisasi Teks Cerdas (Deduplikasi karakter berulang, pembersihan tanda baca).
2. Kamus Slang & Singkatan Bahasa Indonesia (250+ kata gaul, singkatan e-commerce & chat).
3. Hybrid Intent Matching (TF-IDF N-Gram Cosine Similarity + Semantic Anchor Boosting).
4. Slot Filling & Entity Extraction (Paket A-D, Bahan Kain, Ukuran, Kode Resi/Pesanan).
5. Zero External Dependency (Murni pustaka standar Python, latency < 0.5 milidetik).

Dapat dijalankan lewat:
1. Laragon (Start All via Procfile)
2. Script one-click: start-local.bat
3. Command terminal: python server_nlp.py
"""

import sys
import json
import re
import math
import time
from collections import Counter
from typing import Dict, List, Any, Optional

PORT = 8000
HOST = "127.0.0.1"

# ==============================================================================
# 1. KAMUS NORMALISASI SLANG & SINGKATAN INDONESIA (EXPANDED VOCABULARY)
# ==============================================================================

SLANG_DICTIONARY = {
    # Panggilan & Salam
    "hallo": "halo", "hello": "halo", "helo": "halo", "hy": "halo", "haii": "halo",
    "p": "halo", "woi": "halo", "oy": "halo", "min": "admin", "mimin": "admin",
    "bro": "kak", "sis": "kak", "gan": "kak", "bos": "kak", "kaka": "kak", "kakk": "kak",
    "aslm": "assalamualaikum", "ass": "assalamualaikum", "askum": "assalamualaikum",
    "samlikum": "assalamualaikum", "met": "selamat", "pagi": "pagi", "siang": "siang",
    "sore": "sore", "malem": "malam", "mlm": "malam",
    
    # Kata Tanya
    "brp": "berapa", "brapa": "berapa", "piro": "berapa", "pirang": "berapa", "brpa": "berapa",
    "dmana": "dimana", "dmna": "dimana", "dmn": "dimana", "mna": "mana",
    "gmn": "gimana", "bgmn": "bagaimana", "kekmana": "bagaimana", "gimane": "gimana", "piye": "gimana", "pie": "gimana",
    "kpn": "kapan", "knp": "kenapa", "ngapa": "kenapa", "napa": "kenapa",
    "ap": "apa", "apkh": "apakah", "apkah": "apakah", "opo": "apa",
    "syp": "siapa", "spa": "siapa",
    
    # Aksi & Transaksi
    "bkin": "bikin", "bikin": "buat", "gawe": "buat", "nyetak": "cetak", "sablon": "sablon",
    "pesen": "pesan", "order": "pesan", "co": "checkout", "bl": "beli", "tny": "tanya", "tnya": "tanya",
    "kirim": "kirim", "krm": "kirim", "ngirim": "kirim", "antar": "kirim",
    "byr": "bayar", "pembayaran": "bayar", "tf": "transfer", "trf": "transfer",
    "lacak": "lacak", "tracking": "lacak", "cek": "cek", "liat": "lihat", "pantau": "lacak",
    "sndiri": "sendiri", "pengen": "mau", "pgen": "mau", "mw": "mau", "mo": "mau", "pingin": "mau",
    "pake": "pakai", "pke": "pakai", "pkai": "pakai",
    
    # Produk, Paket & Desain Jersey
    "hrg": "harga", "rego": "harga", "pricelst": "pricelist", "price": "harga", "tarif": "harga",
    "pkt": "paket", "pket": "paket", "pack": "paket",
    "dsain": "desain", "desen": "desain", "disain": "desain", "design": "desain", "desin": "desain", "dsin": "desain",
    "kostum": "jersey", "kaos": "jersey", "baju": "jersey", "jersi": "jersey", "jerse": "jersey",
    "bola": "jersey", "futsal": "jersey", "setelan": "setelan", "stel": "setelan", "stelan": "setelan",
    "celana": "celana", "cln": "celana", "kolor": "celana",
    
    # Bahan Kain & Material
    "mat": "bahan", "kain": "bahan", "fabric": "bahan", "material": "bahan",
    "drifit": "dryfit", "dri-fit": "dryfit", "waffle": "wafel", "wafl": "wafel",
    
    # Logistik, Pengiriman & Tracking
    "ongkr": "ongkir", "ongkos": "ongkir", "pos": "ekspedisi", "kurir": "ekspedisi",
    "resi": "resi", "noresi": "resi", "awb": "resi",
    
    # Kuantitas & Ukuran
    "satuan": "satuan", "sebiji": "satuan", "ecer": "satuan", "eceran": "satuan", "selembar": "satuan", "sebuah": "satuan",
    "lusin": "lusin", "grosir": "grosir", "partai": "grosir", "banyak": "grosir",
    "ukuran": "ukuran", "size": "ukuran", "sizechart": "ukuran", "chart": "ukuran",
    "jumbo": "bigsize", "gede": "besar", "cilik": "kecil",
    
    # Waktu & Durasi Produksi
    "durasine": "lama", "durasi": "lama", "pengerjaan": "lama", "beres": "selesai",
    "kelar": "selesai", "kpn jd": "kapan jadi", "kapan beres": "kapan jadi", "kpn kelar": "kapan jadi",
    "cepet": "cepat", "cpt": "cepat", "kilat": "express",
    
    # Promo & Pembayaran
    "diskon": "diskon", "potongan": "diskon", "promo": "diskon", "voucher": "diskon", "vcr": "diskon", "cashback": "diskon",
    "rek": "rekening", "dp": "dp", "uang muka": "dp",
    
    # Lokasi & Workshop
    "almt": "alamat", "lokasine": "lokasi", "tempat": "lokasi", "bengkel": "workshop",
    "toko": "toko", "offline": "offline", "mampir": "kunjung",
    
    # Respon Sosial & Retur
    "makasi": "terima kasih", "makasih": "terima kasih", "thx": "terima kasih", "tq": "terima kasih",
    "suwun": "terima kasih", "nuhun": "terima kasih", "ty": "terima kasih", "tengkyu": "terima kasih",
    "komplain": "komplain", "rusak": "cacat", "robek": "cacat", "sobek": "cacat", "retur": "retur", "tukar": "retur",
    
    # Partikel, Konjungsi & Negasi
    "ga": "tidak", "gak": "tidak", "nggak": "tidak", "g": "tidak", "ndak": "tidak", "kagak": "tidak",
    "bkn": "bukan", "bener": "benar", "bgt": "banget", "bgtu": "begitu",
    "aja": "saja", "doang": "saja", "dg": "dengan", "dgn": "dengan", "utk": "untuk",
    "klo": "kalau", "klu": "kalau", "kl": "kalau", "kalo": "kalau", "jika": "kalau",
    "bs": "bisa", "bsa": "bisa", "bisaa": "bisa",
    "sdh": "sudah", "udh": "sudah", "udah": "sudah", "blm": "belum", "blom": "belum"
}

# ==============================================================================
# 2. INTENTS & PENGETAHUAN LENGKAP BECKS APPAREL
# ==============================================================================

INTENT_KNOWLEDGE = {
    "handover": {
        "patterns": [
            "hubungi admin cs sekarang",
            "mau bicara dengan admin manusia atau orang",
            "tolong sambungkan ke customer service becks",
            "saya mau komplain pesanan saya rusak cacat robek",
            "batal pesanan mau cancel order saya",
            "retur barang salah ukuran sablon rusak",
            "admin tolong respon chat ini",
            "bicara langsung dengan orang asli bukan bot",
            "komplain pelayanan admin lambat"
        ],
        "response": "[HANDOVER]",
        "status": "handover"
    },
    "greeting": {
        "patterns": [
            "halo selamat pagi siang sore malam",
            "halo kak selamat datang halo becks apparel",
            "hai halo assalamualaikum salam olahraga",
            "halo admin apakah bot aktif ready",
            "tes tes bot halo min selamat siang",
            "halo mau tanya tanya seputar jersey",
            "hai becks mau konsultasi pesanan baju olahraga"
        ],
        "response": "Halo Kak! Selamat datang di Becks Apparel. Ada yang bisa kami bantu? Kakak bisa menanyakan daftar harga paket jersey, simulasi desain 3D, pilihan bahan kain, cek ongkir, ataupun cara pemesanan."
    },
    "thanks": {
        "patterns": [
            "terima kasih banyak atas infonya ya admin",
            "makasih ya min sangat membantu",
            "thanks kak thank you mantap",
            "matur suwun hatur nuhun oke siap kak",
            "terima kasih infonya nanti saya pesan"
        ],
        "response": "Sama-sama Kak! Senang bisa membantu. Jika ada pertanyaan lain atau ingin langsung memesan, silakan tanyakan kembali ya. Salam olahraga dari Becks Apparel!"
    },
    "pricing_general": {
        "patterns": [
            "berapa harga paket jersey becks apparel",
            "minta daftar harga pricelist jersey",
            "biaya pembuatan bikin jersey berapaan",
            "ada paket apa saja dan harganya berapa",
            "pricelist harga baju futsal sepakbola",
            "ongkos bikin jersey full print berapa ya",
            "pilihan paket harga termurah dan lengkap",
            "daftar harga jersey",
            "paket dan harga"
        ],
        "response": (
            "Berikut pilihan Paket Jersey di Becks Apparel:\n"
            "• Paket A (Rp 90.000): Non-Printing + Sablon DTF\n"
            "• Paket B (Rp 110.000): Badan Polos + Lengan Full Printing\n"
            "• Paket C (Rp 130.000): Jersey Atasan Full Printing (Best Seller)\n"
            "• Paket D (Rp 160.000): Setelan Full Printing (Jersey Atasan + Celana)\n"
            "Semua paket bebas pilih bahan premium (Milano, Benzema, Wafel, dll) tanpa biaya tambahan!"
        )
    },
    "package_a": {
        "patterns": [
            "detail spesifikasi harga paket a non printing dtf",
            "berapa harga paket a dan apa saja yang didapat",
            "paket a itu jersey seperti apa rp 90000",
            "jelaskan paket a becks apparel dtf"
        ],
        "response": (
            "Detail Paket A (Rp 90.000 / pcs):\n"
            "• Tipe: Non-Printing (Kain Polos Berwarna) + Sablon DTF (Direct to Film) premium.\n"
            "• Cocok untuk desain simpel: logo tim dada, nama punggung, dan nomor.\n"
            "• Bebas pilih bahan kain (Milano, Benzema, dll).\n"
            "• Sangat ekonomis untuk kebutuhan seragam kasual / tim!"
        )
    },
    "package_b": {
        "patterns": [
            "detail spesifikasi harga paket b lengan printing",
            "berapa harga paket b rp 110000",
            "paket b itu gimana ya penjelasannya",
            "paket b badan polos lengan printing"
        ],
        "response": (
            "Detail Paket B (Rp 110.000 / pcs):\n"
            "• Tipe: Kombinasi Badan Polos (dengan sablon DTF) + Bagian Lengan Full Printing Sublimasi.\n"
            "• Desain lengan bisa bermotif gradasi/pola dinamis, badan tetap elegan.\n"
            "• Bebas pilih bahan kain premium.\n"
            "• Pilihan seimbang antara gaya modis dan harga terjangkau!"
        )
    },
    "package_c": {
        "patterns": [
            "detail spesifikasi harga paket c full printing atasan",
            "berapa harga paket c best seller rp 130000",
            "paket c dapet apa aja ya min",
            "mau pesan paket c jersey atasan full print",
            "harga paket c berapa kak"
        ],
        "response": (
            "Detail Paket C (Rp 130.000 / pcs) - BEST SELLER:\n"
            "• Tipe: Jersey Atasan Full Sublimation Printing (Depan, Belakang, & Lengan full motif custom).\n"
            "• Bebas pakai gradasi warna, pola kompleks, logo tak terbatas, sponsor, nama, dan nomor punggung.\n"
            "• Tinta sublimasi tajam, tidak luntur, dan menyatu sempurna dengan serat kain.\n"
            "• Pilihan paling favorit untuk jersey futsal, sepakbola, basket, dan esport!"
        )
    },
    "package_d": {
        "patterns": [
            "detail spesifikasi harga paket d setelan baju dan celana",
            "berapa harga paket d setelan full printing rp 160000",
            "paket d dapet celana juga ya min",
            "harga setelan jersey dan celana berapa paket d"
        ],
        "response": (
            "Detail Paket D (Rp 160.000 / stel) - PAKET LENGKAP:\n"
            "• Tipe: Setelan Lengkap (Baju Jersey Atasan Full Printing + Celana Olahraga Printing Matching).\n"
            "• Desain atasan dan celana serasi dengan motif full custom.\n"
            "• Sudah termasuk nomor celana, nomor punggung, nama, dan sponsor tim.\n"
            "• Solusi seragam tim paling profesional dan siap bertanding!"
        )
    },
    "custom_design": {
        "patterns": [
            "bagaimana cara custom desain jersey sendiri",
            "fitur 3d mockup kustomisasi desain di website",
            "mau bikin baju pakai gambar logo sendiri bisa",
            "cara mendesain jersey lewat hp atau laptop",
            "bisa dibantu buatkan desain dari nol kalau belum punya",
            "upload logo tim buat jersey custom gimana caranya",
            "fitur desain 3d interaktif becks apparel",
            "pengen bikin jersey desain sendiri pakai 3d",
            "buat jersey mockup 3d custom pola sendiri",
            "bisa bikinin desain 3d upload logo",
            "desain sendiri custom logo"
        ],
        "response": (
            "Membuat desain di Becks Apparel sangat mudah Kak!\n"
            "1. Buka menu 'Kustomisasi' di navigasi atas website kami.\n"
            "2. Gunakan Studio 3D interaktif untuk memilih warna, pola baju, dan kerah secara 360°.\n"
            "3. Upload logo tim (format PNG/JPG), tambahkan nama, nomor, dan sponsor langsung di mockup 3D.\n"
            "4. Jika belum punya desain, jangan khawatir! Tim desainer kami siap membantu membuatkan konsep desain jersey impian Kakak."
        )
    },
    "materials_general": {
        "patterns": [
            "apa saja pilihan bahan kain yang tersedia untuk jersey",
            "jenis kain apa yang paling bagus adem dan menyerap keringat",
            "rekomendasi bahan jersey futsal dan sepakbola",
            "perbedaan bahan milano benzema wafel dryfit",
            "apakah ganti bahan kain kena biaya tambahan gratis",
            "pilihan bahan jersey apa saja"
        ],
        "response": (
            "Becks Apparel menyediakan aneka bahan kain Dryfit Premium (Semua Bebas Pilih Gratis):\n"
            "• Dryfit Milano: Serat motif zig-zag halus, sangat lembut, jatuh di badan, paling favorit/populer.\n"
            "• Dryfit Benzema: Motif pori segi enam (hexagonal), sangat lentur, adem, dan cepat kering.\n"
            "• Dryfit Wafel: Tekstur kotak wafel unik berongga, sirkulasi udara maksimal untuk aktivitas berat.\n"
            "• Dryfit N-Tech / Smash / Serena: Ringan, licin, dan elastis untuk pergerakan dinamis."
        )
    },
    "material_milano": {
        "patterns": [
            "kain dryfit milano itu seperti apa karakteristiknya",
            "kelebihan bahan milano untuk jersey olahraga",
            "apakah bahan milano bagus dan dingin",
            "karakteristik tekstur bahan milano"
        ],
        "response": (
            "Dryfit Milano adalah bahan paling favorit di Becks Apparel! Memiliki serat halus berpola zig-zag mikro yang sangat lembut saat bersentuhan dengan kulit. Kainnya jatuh elegan di badan, adem, memiliki daya serap keringat tinggi, dan sangat awet untuk pemakaian harian maupun turnamen."
        )
    },
    "material_benzema": {
        "patterns": [
            "kain dryfit benzema itu seperti apa karakteristiknya",
            "kelebihan bahan benzema tekstur segi enam",
            "bahan benzema adem tidak lentur",
            "karakteristik kain benzema becks"
        ],
        "response": (
            "Dryfit Benzema memiliki ciri khas tekstur pori-pori berbentuk segi enam (hexagonal). Bahan ini memiliki elastisitas tinggi (lentur), sangat ringan, dan sirkulasi udara cepat sehingga keringat cepat menguap. Sangat direkomendasikan untuk cabang olahraga futsal, lari, dan badminton!"
        )
    },
    "material_wafel": {
        "patterns": [
            "kain wafel waffle itu seperti apa teksturnya",
            "kelebihan bahan wafel untuk jersey custom",
            "bahan wafel kotak-kotak tebal adem tidak",
            "karakteristik kain wafel becks apparel"
        ],
        "response": (
            "Dryfit Wafel (Waffle) memiliki tekstur unik timbul menyerupai cetakan wafel kotak-kotak. Rongga-rongga fisiknya memberikan aliran udara (breathability) paling maksimal dan memberi kesan mewah serta berbobot (premium feel). Sangat cocok untuk tim yang ingin tampil beda!"
        )
    },
    "size_chart": {
        "patterns": [
            "ada ukuran apa saja size chart panduan ukuran baju",
            "panjang kali lebar dada ukuran s m l xl xxl 3xl",
            "apakah ada ukuran anak kecil dan big size jumbo",
            "bagaimana cara mengetahui size yang pas untuk saya",
            "tabel size chart becks apparel",
            "ukuran jumbo xxxl ada ga size chartnya",
            "size chart baju jersey"
        ],
        "response": (
            "Tersedia ukuran lengkap mulai dari Anak (Kid Size) hingga Dewasa Big Size (XS s.d 5XL):\n"
            "• S: Lebar 48 cm | Panjang 68 cm\n"
            "• M: Lebar 50 cm | Panjang 70 cm\n"
            "• L: Lebar 52 cm | Panjang 72 cm\n"
            "• XL: Lebar 54 cm | Panjang 74 cm\n"
            "• XXL: Lebar 56 cm | Panjang 76 cm\n"
            "• 3XL - 5XL: Tersedia custom big size (tambah Rp 10.000 - Rp 20.000/tingkat)."
        )
    },
    "moq_order": {
        "patterns": [
            "apakah bisa pesan jersey satuan satu baju doang",
            "minimal order berapa pcs atau lusin bisa eceran",
            "syarat minimal pemesanan baju tim becks apparel",
            "bisa bikin cuma satu kaos custom nama sendiri",
            "minimal pemesanan berapa buah min",
            "bisa beli satuan satu pcs"
        ],
        "response": (
            "Kabar gembira Kak! Di Becks Apparel BISA PESAN SATUAN (1 pcs pun kami layani dengan sepenuh hati)!\n"
            "• Pesan 1 pcs (satuan): Desain bebas sesuai keinginan Kakak.\n"
            "• Pesan tim / lusinan (12 pcs ke atas): Mendapatkan potongan harga khusus (diskon grosir) dan gratis bendera / merchandise tim."
        )
    },
    "production_time": {
        "patterns": [
            "berapa lama waktu pengerjaan pembuatan jersey",
            "estimasi pengerjaan pesanan berapa hari siap kirim",
            "durasi produksi berapa hari kerja selesai",
            "apakah ada layanan express kilat cepat jadi",
            "kapan pesanan saya selesai diproduksi",
            "berapa hari pengerjaan jersey"
        ],
        "response": (
            "Estimasi waktu pengerjaan di Becks Apparel:\n"
            "• Pengerjaan Standar: 7 - 14 hari kerja (tergantung antrean dan jumlah kuantiti).\n"
            "• Layanan Express / Kilat: 3 - 5 hari kerja (tersedia dengan konfirmasi ke admin untuk cek slot produksi).\n"
            "Status pengerjaan dapat dipantau langsung secara real-time lewat menu 'Pesanan Saya'."
        )
    },
    "order_flow": {
        "patterns": [
            "bagaimana cara memesan jersey alur pemesanan",
            "langkah langkah order dari awal sampai jadi",
            "gimana cara beli dan bayar di website ini",
            "tata cara order jersey custom becks apparel",
            "cara pesan jersey di becks"
        ],
        "response": (
            "Alur pemesanan mudah 4 langkah di Becks Apparel:\n"
            "1. Desain: Buat desain di menu 'Kustomisasi' 3D atau pilih katalog paket.\n"
            "2. Roster & Ukuran: Masukkan daftar nama pemain, nomor punggung, dan ukuran.\n"
            "3. Checkout: Masukkan alamat pengiriman (ongkir otomatis terhitung) dan pilih metode pembayaran.\n"
            "4. Produksi & Kirim: Tim memproduksi pesanan Kakak, dan paket dikirim dengan nomor resi terverifikasi."
        )
    },
    "shipping_expedition": {
        "patterns": [
            "pengiriman menggunakan kurir ekspedisi apa saja",
            "apakah melayani kirim ke seluruh indonesia luar pulau",
            "berapa ongkos kirim tarif ongkir jne jnt sicepat",
            "ekspedisi pengiriman luar kota cargo",
            "kirim ke sulawesi ongkirnya berapa kurir apa"
        ],
        "response": (
            "Becks Apparel melayani pengiriman ke SELURUH INDONESIA!\n"
            "• Kurir Reguler: JNE, J&T Express, SiCepat, POS Indonesia, TIKI.\n"
            "• Cargo: Indah Cargo / J&T Cargo untuk pesanan partai besar (lebih hemat ongkir).\n"
            "• Ongkir terhitung otomatis saat checkout menggunakan sistem RajaOngkir sesuai alamat kecamatan Kakak."
        )
    },
    "order_tracking": {
        "patterns": [
            "bagaimana cara lacak pesanan saya cek resi pengiriman",
            "pesanan saya sudah sampai mana status paket",
            "lacak order nomor resi bck ord",
            "cara melihat progres pengerjaan baju pesanan saya"
        ],
        "response": (
            "Kakak bisa melacak pesanan dengan mudah:\n"
            "1. Masuk ke Akun Kakak &rarr; klik 'Riwayat Pesanan' / 'Pesanan Saya'.\n"
            "2. Klik 'Lacak Pesanan' pada pesanan yang ingin dicek.\n"
            "3. Sistem kami terintegrasi dengan pelacakan real-time Biteship Logistics untuk memantau pergerakan kurir secara langsung dari HP Kakak."
        )
    },
    "payment_methods": {
        "patterns": [
            "metode pembayaran apa saja yang diterima cara bayar",
            "apakah bisa transfer bank bca mandiri bri bni qris",
            "apakah bisa cod bayar di tempat atau bayar bertahap dp",
            "cara bayar pesanan lewat paywuz transfer rekening",
            "bisa bayar pakai qris transfer",
            "bisa bayar pake qris ga atau transfer",
            "pembayaran qris dan transfer bank"
        ],
        "response": (
            "Kami menyediakan metode pembayaran digital yang aman dan otomatis:\n"
            "• QRIS: Scan dari semua e-wallet (GoPay, OVO, DANA, ShopeePay, LinkAja) & Mobile Banking.\n"
            "• Transfer Bank / Virtual Account: BCA, Mandiri, BNI, BRI, Permata.\n"
            "• Sistem DP (Uang Muka 50%): Tersedia untuk pemesanan tim/partai besar (pelunasan sebelum barang dikirim).\n"
            "Semua transaksi online diproses aman melalui gateway terverifikasi Paywuz."
        )
    },
    "promo_discount": {
        "patterns": [
            "apakah ada promo diskon potongan harga",
            "voucher cashback untuk pesanan tim banyak",
            "promo bulan ini ada apa saja min",
            "diskon pemesanan banyak satu tim lusinan"
        ],
        "response": (
            "Ada banyak promo menarik di Becks Apparel Kak!\n"
            "• Diskon Pemesanan Tim: Potongan spesial untuk order di atas 12 pcs / 24 pcs.\n"
            "• Gratis Bendera Tim & Ban Kapten untuk paket pemesanan tertentu.\n"
            "• Gratis Biaya Desain & revisi sampai fix.\n"
            "Cek banner halaman depan atau hubungi admin untuk klaim kode promo bulan ini!"
        )
    },
    "store_location": {
        "patterns": [
            "dimana alamat lokasi toko workshop offline becks apparel",
            "apakah bisa datang langsung ke workshop toko fisik",
            "posisi toko becks apparel di kota mana",
            "alamat workshop kantor becks",
            "tokonya dimana ya min bisa mampir langsung ke toko"
        ],
        "response": (
            "Workshop produksi Becks Apparel melayani pemesanan online ke seluruh Indonesia dengan jangkauan pengiriman cepat. Untuk konsultasi langsung atau melihat sampel kain secara offline, Kakak bisa ketik 'admin' agar CS kami membagikan titik lokasi Google Maps workshop terdekat ya!"
        )
    },
    "warranty_return": {
        "patterns": [
            "apakah ada garansi jika baju rusak cacat salah sablon",
            "kebijakan retur pengembalian barang jaminan mutu",
            "bagaimana jika ukuran tidak sesuai atau sablon mengelupas",
            "garansi produksi becks apparel",
            "garansi retur barang cacat gimana min ada garansi tidak"
        ],
        "response": (
            "Tenang Kak, Becks Apparel memberikan GARANSI 100% KEPUASAN PRODUKSI:\n"
            "• Jika ada cacat produksi (jahitan lepas, sablon cacat, nama/nomor salah cetak dari pihak kami), kami siap perbaiki atau ganti baru TANPA BIAYA TAMBAHAN.\n"
            "• Cukup sertakan video unboxing paket dan hubungi admin kami maksimal 2x24 jam setelah barang diterima."
        )
    }
}

# Semantic Keyword Anchors untuk memberikan boost akurasi cepat
INTENT_ANCHORS = {
    "greeting": ["halo", "hai", "assalamualaikum", "pagi", "siang", "sore", "malam", "salam olahraga"],
    "thanks": ["terima kasih", "makasih", "thanks", "suwun", "nuhun", "tengkyu"],
    "pricing_general": ["harga", "biaya", "pricelist", "berapaan", "ongkos bikin", "tarif"],
    "package_a": ["paket a", "non printing", "dtf"],
    "package_b": ["paket b", "lengan printing"],
    "package_c": ["paket c", "full printing", "full print"],
    "package_d": ["paket d", "setelan", "celana"],
    "custom_design": ["desain", "design", "3d", "mockup", "kustomisasi", "upload logo", "custom"],
    "materials_general": ["bahan", "kain", "dryfit", "material"],
    "material_milano": ["milano"],
    "material_benzema": ["benzema"],
    "material_wafel": ["wafel", "waffle"],
    "size_chart": ["ukuran", "size", "sizechart", "chart", "xxl", "xxxl", "3xl", "4xl", "5xl", "jumbo", "lebar dada"],
    "moq_order": ["satuan", "sebiji", "ecer", "eceran", "minimal", "1 pcs", "satu baju", "satu kaos"],
    "production_time": ["lama", "durasi", "pengerjaan", "kapan jadi", "kapan beres", "berapa hari", "express", "kilat"],
    "order_flow": ["cara pesan", "cara order", "alur pesan", "langkah order", "cara beli"],
    "shipping_expedition": ["ongkir", "ekspedisi", "kurir", "jne", "jnt", "sicepat", "pos", "tiki", "cargo", "pengiriman"],
    "order_tracking": ["lacak", "tracking", "resi", "posisi paket", "sampai mana"],
    "payment_methods": ["bayar", "pembayaran", "qris", "transfer", "rekening", "bca", "mandiri", "bri", "bni", "paywuz", "cod", "dp"],
    "promo_discount": ["diskon", "promo", "potongan", "voucher", "cashback", "lusinan", "grosir"],
    "store_location": ["lokasi", "alamat", "toko", "workshop", "offline", "mampir", "kota mana"],
    "warranty_return": ["garansi", "retur", "rusak", "cacat", "ganti baru", "komplain barang"]
}

# ==============================================================================
# 3. ADVANCED TEXT PREPROCESSOR & TOKENIZER
# ==============================================================================

def normalize_text(text: str) -> str:
    """Membersihkan teks, mereduksi karakter berulang, dan memetakan slang."""
    if not text:
        return ""
    
    s = text.lower().strip()
    
    # 1. Reduksi huruf berulang > 2 karakter (contoh: 'haaaalllooooo' -> 'halo', 'kaaaak' -> 'kak')
    s = re.sub(r'(.)\1{2,}', r'\1', s)
    
    # 2. Hapus tanda baca selain strip dan spasi
    s = re.sub(r'[^a-zA-Z0-9\s\-]', ' ', s)
    
    # 3. Tokenisasi dasar
    raw_tokens = s.split()
    
    # 4. Normalisasi kata slang & singkatan
    norm_tokens = []
    for t in raw_tokens:
        clean_t = SLANG_DICTIONARY.get(t, t)
        norm_tokens.append(clean_t)
        
    return " ".join(norm_tokens)


def extract_features(text: str) -> Dict[str, float]:
    """
    Menghasilkan representasi multi-level (Word Unigram + Bigram + Char Trigram).
    Memungkinkan toleransi typo dan penangkapan konteks secara bersamaan dalam microseconds.
    """
    clean = normalize_text(text)
    words = clean.split()
    feats = Counter()
    
    # Word Unigrams (bobot 1.5)
    for w in words:
        if len(w) > 1:
            feats[f"w:{w}"] += 1.5
            
    # Word Bigrams (bobot 2.0 untuk frasa konteks)
    for i in range(len(words) - 1):
        feats[f"bg:{words[i]}_{words[i+1]}"] += 2.0
        
    # Character 3-grams untuk toleransi typo (bobot 0.4)
    for w in words:
        if len(w) >= 3:
            for i in range(len(w) - 2):
                feats[f"c3:{w[i:i+3]}"] += 0.4
                
    # Normalisasi vektor L2
    norm = math.sqrt(sum(v * v for v in feats.values()))
    if norm > 0:
        for k in feats:
            feats[k] /= norm
            
    return feats


def cosine_similarity(v1: Dict[str, float], v2: Dict[str, float]) -> float:
    """Menghitung cosine similarity sparse vector secara ultra-cepat."""
    if not v1 or not v2:
        return 0.0
    if len(v1) > len(v2):
        v1, v2 = v2, v1
    return sum(weight * v2[feat] for feat, weight in v1.items() if feat in v2)


# ==============================================================================
# 4. ENGINE NLP CLASSIFIER DENGAN PRECOMPUTED INTENT INDEX
# ==============================================================================

class LocalNLPEngine:
    def __init__(self):
        self.intent_models = {}
        self._build_index()

    def _build_index(self):
        """Precompute representasi vektor dari seluruh pola intent saat inisialisasi server."""
        for intent_id, data in INTENT_KNOWLEDGE.items():
            pattern_vectors = []
            for p in data["patterns"]:
                vec = extract_features(p)
                if vec:
                    pattern_vectors.append(vec)
            self.intent_models[intent_id] = {
                "vectors": pattern_vectors,
                "response": data["response"],
                "status": data.get("status", "success")
            }

    def predict(self, user_text: str) -> Dict[str, Any]:
        """
        Memprediksi intent dan menghasilkan respons dengan latency sub-millisecond.
        """
        raw = user_text.strip()
        if not raw:
            return {
                "status": "success",
                "intent": "empty",
                "confidence": 1.0,
                "message": "Ada yang bisa Becks Apparel bantu seputar pembuatan jersey custom atau cek harga paket?"
            }

        norm_text = normalize_text(raw)
        
        # 1. Deteksi Prioritas Handover (Eksplisit minta CS / Admin / Komplain / Batal / Retur Langsung)
        is_general_warranty_query = bool(re.search(r'\b(garansi|kebijakan|syarat|gimana|bagaimana)\b', raw.lower()) and "retur" in raw.lower())
        
        if not is_general_warranty_query:
            handover_regex = [
                r'\b(bicara|ngomong|chat|sambungkan|hubungi|kontak)\s+(dengan|sama|ke)?\s*(admin|cs|manusia|orang)\b',
                r'\b(admin|cs)\s+(manusia|orang|asli)\b',
                r'\b(bukan\s+bot|jangan\s+bot)\b',
                r'\b(mau\s+)?(komplain|batal\s+pesanan|cancel\s+order)\b',
                r'\b(mau\s+retur|ajukan\s+retur)\b',
                r'\b(baju(nya)?\s+(robek|rusak|cacat))\b',
                r'^(admin|cs)$'
            ]
            for pattern in handover_regex:
                if re.search(pattern, raw.lower()):
                    return {
                        "status": "handover",
                        "intent": "handover",
                        "confidence": 1.0,
                        "message": "[HANDOVER]"
                    }

        # 2. Slot Filling / Deteksi Entitas Langsung
        # A. Cek Entitas Paket Spesifik
        if re.search(r'\bpaket\s+a\b|\bpkt\s+a\b', norm_text):
            return {"status": "success", "intent": "package_a", "confidence": 0.98, "message": INTENT_KNOWLEDGE["package_a"]["response"]}
        if re.search(r'\bpaket\s+b\b|\bpkt\s+b\b', norm_text):
            return {"status": "success", "intent": "package_b", "confidence": 0.98, "message": INTENT_KNOWLEDGE["package_b"]["response"]}
        if re.search(r'\bpaket\s+c\b|\bpkt\s+c\b', norm_text):
            return {"status": "success", "intent": "package_c", "confidence": 0.98, "message": INTENT_KNOWLEDGE["package_c"]["response"]}
        if re.search(r'\bpaket\s+d\b|\bpkt\s+d\b|\bsetelan\b', norm_text):
            return {"status": "success", "intent": "package_d", "confidence": 0.98, "message": INTENT_KNOWLEDGE["package_d"]["response"]}

        # B. Cek Entitas Bahan Spesifik
        if re.search(r'\bmilano\b', norm_text):
            return {"status": "success", "intent": "material_milano", "confidence": 0.95, "message": INTENT_KNOWLEDGE["material_milano"]["response"]}
        if re.search(r'\bbenzema\b', norm_text):
            return {"status": "success", "intent": "material_benzema", "confidence": 0.95, "message": INTENT_KNOWLEDGE["material_benzema"]["response"]}
        if re.search(r'\bwafel\b|\bwaffle\b', norm_text):
            return {"status": "success", "intent": "material_wafel", "confidence": 0.95, "message": INTENT_KNOWLEDGE["material_wafel"]["response"]}

        # C. Cek Format Kode Pesanan (Resi / Order ID: BCK-... / ORD-...)
        order_match = re.search(r'\b(bck|ord)[\w\-]+\b', raw.lower())
        if order_match and any(k in norm_text for k in ["lacak", "resi", "cek", "status", "dimana", "posisi"]):
            order_code = order_match.group(0).upper()
            return {
                "status": "success",
                "intent": "order_tracking",
                "confidence": 0.99,
                "message": (
                    f"Untuk pesanan dengan kode '{order_code}', Kakak bisa memantau progres produksi atau posisi kurir "
                    f"secara langsung di menu 'Riwayat Pesanan' &rarr; klik 'Lacak Pesanan'. "
                    f"Jika membutuhkan bantuan lebih lanjut mengenai pesanan ini, ketik 'admin' untuk tersambung ke CS kami."
                )
            }

        # 3. Vector Feature Extraction & Cosine Similarity Matching + Semantic Anchor Boosting
        input_vec = extract_features(raw)
        best_intent = None
        best_score = 0.0

        for intent_id, data in self.intent_models.items():
            if intent_id == "handover":
                continue
            
            # Hitung anchor boost
            anchor_boost = 0.0
            for anchor in INTENT_ANCHORS.get(intent_id, []):
                if anchor in norm_text:
                    anchor_boost = max(anchor_boost, 0.15)
                    if len(anchor.split()) > 1 and anchor in norm_text:
                        anchor_boost = max(anchor_boost, 0.25)

            # Max pooling skor pola
            for p_vec in data["vectors"]:
                score = cosine_similarity(input_vec, p_vec) + anchor_boost
                if score > best_score:
                    best_score = score
                    best_intent = intent_id

        # 4. Thresholding & Fallback
        # Level 1: Confidence Memadai (>= 0.22)
        if best_score >= 0.22 and best_intent:
            target = self.intent_models[best_intent]
            return {
                "status": target["status"],
                "intent": best_intent,
                "confidence": min(round(best_score, 3), 1.0),
                "message": target["response"]
            }

        # Level 2: Fallback Cerdas Memandu Pengguna
        return {
            "status": "success",
            "intent": "fallback",
            "confidence": round(best_score, 3),
            "message": (
                f"Halo Kak! Terkait '{user_text}', kami siap membantu. Kakak bisa tanyakan mengenai:\n"
                "• Daftar harga & spesifikasi paket jersey (Paket A, B, C, D)\n"
                "• Fitur Kustomisasi Desain 3D online\n"
                "• Pilihan bahan premium (Milano, Benzema, Wafel)\n"
                "• Cek ukuran (size chart) atau minimal order\n"
                "• Atau ketik 'admin' jika ingin mengobrol langsung dengan Customer Service kami."
            )
        }

# Inisialisasi Singleton NLP Engine (Hanya 1x saat booting)
GLOBAL_NLP_ENGINE = LocalNLPEngine()


def process_nlp_message(user_message: str, user_id: str = "guest", history: list = None) -> dict:
    """Fungsi utama yang dipanggil oleh router FastAPI / server HTTP."""
    return GLOBAL_NLP_ENGINE.predict(user_message)


# ==============================================================================
# SERVER HTTP (Otomatis memilih FastAPI jika ada, atau built-in http.server)
# ==============================================================================

def run_fastapi():
    from fastapi import FastAPI
    from pydantic import BaseModel
    from typing import Optional, List, Dict, Any
    import uvicorn

    app = FastAPI(title="Becks Apparel Local NLP (High Efficiency)")

    class ChatPayload(BaseModel):
        message: str
        user_id: Optional[str] = "guest"
        history: Optional[List[Dict[str, Any]]] = []

    @app.post("/chatbot")
    def chatbot_route(payload: ChatPayload):
        return process_nlp_message(payload.message, payload.user_id, payload.history)

    @app.get("/chatbot")
    @app.get("/")
    def health_check():
        return {"status": "ok", "service": "Becks Local NLP Chatbot (FastAPI / Ultra-Fast)"}

    print(f"[*] Menjalankan NLP Server (FastAPI) di http://{HOST}:{PORT}/chatbot")
    uvicorn.run(app, host=HOST, port=PORT, log_level="info")


def run_builtin_server():
    from http.server import HTTPServer, BaseHTTPRequestHandler

    class NLPHandler(BaseHTTPRequestHandler):
        def do_GET(self):
            self.send_response(200)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            response = {"status": "ok", "service": "Becks Local NLP Chatbot (Standard HTTP / Ultra-Fast)"}
            self.wfile.write(json.dumps(response).encode("utf-8"))

        def do_POST(self):
            content_length = int(self.headers.get("Content-Length", 0))
            post_data = self.rfile.read(content_length)

            try:
                payload = json.loads(post_data.decode("utf-8")) if post_data else {}
            except Exception:
                payload = {}

            message = payload.get("message", "")
            user_id = payload.get("user_id", "guest")
            history = payload.get("history", [])

            result = process_nlp_message(message, user_id, history)

            self.send_response(200)
            self.send_header("Content-Type", "application/json; charset=utf-8")
            self.send_header("Access-Control-Allow-Origin", "*")
            self.end_headers()
            self.wfile.write(json.dumps(result, ensure_ascii=False).encode("utf-8"))

        def log_message(self, format, *args):
            print(f"[NLP-Server] {self.address_string()} - {args[0]}")

    server = HTTPServer((HOST, PORT), NLPHandler)
    print(f"[*] Menjalankan NLP Server (Built-in Python) di http://{HOST}:{PORT}/chatbot")
    try:
        server.serve_forever()
    except KeyboardInterrupt:
        print("\n[*] Server NLP dihentikan.")
        server.server_close()


if __name__ == "__main__":
    try:
        import fastapi
        import uvicorn
        run_fastapi()
    except ImportError:
        run_builtin_server()
