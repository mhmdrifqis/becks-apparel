# Dokumentasi Automated Testing - Becks Apparel

Dokumen ini berisi panduan lengkap, inventaris test case, log temuan, dan petunjuk eksekusi pengujian otomatis (*automated testing*) untuk aplikasi e-commerce **Becks Apparel** (Laravel 11.x + Filament v3.x + MySQL).

---

## 1. Ringkasan Eksekutif & Lingkungan Pengujian

Pengujian dilakukan menggunakan **PHPUnit 10** dengan pendekatan *Clean Test Architecture*:
- **Database Khusus Pengujian**: Pengujian dijalankan di database terisolasi `becksapparel_test` pada MySQL 8.0.30 (Laragon). Database produksi/development (`becks_apparel`) tetap utuh dan aman dari modifikasi.
- **Konfigurasi Environment**: Diatur melalui `phpunit.xml` dan `.env.testing` dengan driver cache `array`, queue `sync`, session `array`, serta mock third-party credentials.
- **External Services Mocking**: Seluruh layanan pihak ketiga (RajaOngkir, Biteship, Gemini AI NLP, Fonnte WhatsApp) di-mock menggunakan `Http::fake()` dan environment stub sehingga test berjalan cepat, deterministik, dan dapat dijalankan tanpa koneksi internet aktif.

### Metrik Hasil Pengujian Terakhir
| Parameter | Hasil |
|---|---|
| **Total Test Suites** | 2 Suite (Unit & Feature) |
| **Total File Test** | 13 File Test |
| **Total Test Methods** | **99 Tests** |
| **Total Assertions** | **229 Assertions** |
| **Failures / Errors** | **0 (100% Lulus)** |
| **Execution Time** | ~46.02 detik |
| **Memory Peak** | 88.00 MB |

---

## 2. Tabel Inventaris Test Case

### A. Unit Tests (`tests/Unit/`)

| File Test | Kasus Uji | Deskripsi Validasi | Status |
|---|---|---|:---:|
| [PhoneHelperTest.php](file:///d:/laragon/www/becks-apparel-deploy/tests/Unit/PhoneHelperTest.php) | `test_format_nomor_telepon_awalan_0_menjadi_62` | Memastikan awalan `0812...` dikonversi ke format internasional `62812...`. | **PASS** |
| | `test_format_nomor_telepon_awalan_plus_62_menjadi_62` | Memastikan tanda plus `+62812...` dibersihkan menjadi `62812...`. | **PASS** |
| | `test_nomor_telepon_dengan_spasi_atau_dash_dibersihkan` | Memastikan pembersih karakter strip dan spasi berfungsi normal. | **PASS** |
| | `test_nomor_telepon_kosong_atau_null_mengembalikan_null` | Memastikan nilai kosong menghasilkan `null` secara aman. | **PASS** |
| [OrderCalculationTest.php](file:///d:/laragon/www/becks-apparel-deploy/tests/Unit/OrderCalculationTest.php) | `test_perhitungan_subtotal_harga_paket_dan_material` | Memvalidasi rumus `(base_price + additional_price) * quantity`. | **PASS** |
| | `test_perhitungan_grand_total_pesanan_dengan_ongkir` | Memvalidasi akumulasi `subtotal + shipping_cost`. | **PASS** |
| | `test_perhitungan_dengan_upgrade_tambahan` | Memvalidasi penjumlahan opsi custom kerah, sablon, dan aksesoris. | **PASS** |
| [StockValidationTest.php](file:///d:/laragon/www/becks-apparel-deploy/tests/Unit/StockValidationTest.php) | `test_validasi_kuantiti_minimal_pemesanan_harus_lebih_dari_nol` | Memastikan kuantiti pemesanan `>= 1`. | **PASS** |
| | `test_validasi_kuantiti_maksimal_pemesanan_yang_wajar` | Memastikan batas wajar stok (kuantiti `<= 10.000`). | **PASS** |
| | `test_kuantiti_pecahan_atau_string_tidak_valid` | Memastikan input non-integer atau string tidak valid. | **PASS** |
| [ShippingFormatTest.php](file:///d:/laragon/www/becks-apparel-deploy/tests/Unit/ShippingFormatTest.php) | `test_pemetaan_kode_kurir_ke_huruf_kapital` | Memvalidasi standardisasi kurir `jne` -> `JNE`, `pos` -> `POS`. | **PASS** |
| | `test_format_estimasi_hari_pengiriman` | Memvalidasi formatting estimasi kedatangan paket (ETD). | **PASS** |
| | `test_pembersihan_nomor_resi_dari_spasi_liar` | Memastikan string nomor resi di-`trim` secara benar. | **PASS** |

---

### B. Feature Tests (`tests/Feature/`)

#### 1. Autentikasi & Akun (`AuthTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_pengguna_bisa_melihat_halaman_login` | Akses `GET /login` menghasilkan HTTP 200. | **PASS** |
| `test_pengguna_bisa_login_dengan_kredensial_valid` | Input email/username + password benar redirect ke dashboard. | **PASS** |
| `test_pengguna_gagal_login_dengan_password_salah` | Password salah ditolak dengan pesan error kredensial. | **PASS** |
| `test_pengguna_bisa_logout` | Sesi autentikasi dihapus dan redirect ke halaman awal. | **PASS** |
| `test_pengguna_bisa_melihat_halaman_register` | Akses `GET /register` menghasilkan HTTP 200. | **PASS** |
| `test_registrasi_step_1_mengirim_otp_melalui_whatsapp` | Pengguna menerima OTP ke nomor telepon yang diinput. | **PASS** |
| `test_registrasi_step_2_verifikasi_otp_berhasil_membuat_user` | OTP valid mengaktifkan akun dan memberikan role `Pelanggan`. | **PASS** |
| `test_registrasi_gagal_jika_otp_salah` | OTP tidak valid mengembalikan pesan kegagalan verifikasi. | **PASS** |
| `test_halaman_profil_memerlukan_autentikasi` | Guest diredirect ke login saat mengakses `/customer/profile`. | **PASS** |
| `test_pengguna_bisa_memperbarui_nama_dan_nomor_telepon` | Update profil tersimpan di database. | **PASS** |

#### 2. Katalog & Produk (`ProductTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_pengguna_bisa_melihat_halaman_katalog` | Menampilkan daftar paket produk yang aktif. | **PASS** |
| `test_pengguna_bisa_memfilter_katalog_berdasarkan_kategori` | Filter produk `jersey`, `jacket`, dll berfungsi akurat. | **PASS** |
| `test_pengguna_bisa_mencari_produk_berdasarkan_nama` | Pencarian teks query produk menghasilkan data yang cocok. | **PASS** |
| `test_pengguna_bisa_melihat_detail_produk_dan_pilihan_bahan` | Detail produk memuat paket, bahan baku, dan daftar upgrade. | **PASS** |

#### 3. Keranjang Belanja (`CartTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_halaman_keranjang_memerlukan_login` | Proteksi middleware auth pada halaman keranjang. | **PASS** |
| `test_pengguna_bisa_menambahkan_produk_ke_keranjang` | Tambah paket + material ke tabel `cart_items`. | **PASS** |
| `test_pengguna_bisa_melihat_item_dalam_keranjang` | Rendering daftar item, quantity, dan subtotal pemesan. | **PASS** |
| `test_pengguna_bisa_memperbarui_kuantiti_di_keranjang` | Update quantity cart item secara asinkron/PATCH. | **PASS** |
| `test_pengguna_bisa_menghapus_item_dari_keranjang` | Item terhapus dan hilang dari database `cart_items`. | **PASS** |
| `test_pengguna_tidak_bisa_mengubah_keranjang_milik_pengguna_lain` | Perlindungan otorisasi terhadap IDOR cart user lain. | **PASS** |
| `test_validasi_gagal_jika_menambahkan_kuantiti_kurang_dari_satu` | Mencegah kuantiti 0 atau negatif. | **PASS** |
| `test_menambahkan_produk_yang_sama_meningkatkan_kuantiti` | Idempoten akumulasi quantity saat produk & material sama. | **PASS** |

#### 4. Sistem Alamat Tersimpan (`AddressTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_pengguna_bisa_melihat_daftar_alamat_tersimpan` | Menampilkan seluruh alamat milik user. | **PASS** |
| `test_pengguna_bisa_menambahkan_alamat_baru` | Form tambah alamat menyimpan provinsi, kota, dan kode pos. | **PASS** |
| `test_tambah_alamat_gagal_jika_field_wajib_kosong` | Validasi error pada field alamat yang belum terisi. | **PASS** |
| `test_pengguna_bisa_menjadikan_alamat_sebagai_alamat_utama` | Update status `is_primary = true` dan reset alamat lain. | **PASS** |
| `test_pengguna_bisa_menghapus_alamat_tersimpan` | Alamat berhasil dihapus dari tabel `user_addresses`. | **PASS** |
| `test_pengguna_tidak_bisa_menghapus_alamat_milik_pengguna_lain` | Pencegahan IDOR hapus alamat user lain. | **PASS** |

#### 5. Integrasi Ongkos Kirim & Resi (`ShippingTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_pengguna_bisa_mengambil_daftar_provinsi_dengan_mock` | API RajaOngkir di-mock dan mengembalikan array provinsi. | **PASS** |
| `test_pengguna_bisa_mengambil_daftar_kota_dengan_mock` | Endpoint kota mengembalikan list kota berdasarkan `province_id`. | **PASS** |
| `test_pengguna_bisa_menghitung_ongkos_kirim_manual_dengan_mock` | Hitung ongkir JNE/POS/TIKI mengembalikan opsi layanan & biaya. | **PASS** |
| `test_hitung_ongkir_gagal_jika_parameter_tujuan_dan_berat_kosong` | Validasi parameter wajib sebelum memanggil endpoint luar. | **PASS** |
| `test_pengguna_bisa_melacak_resi_pesanan` | Mock tracking Biteship mengembalikan status kurir & riwayat transit. | **PASS** |
| `test_lacak_resi_gagal_jika_nomor_resi_belum_diinput_oleh_admin` | Respon 400 rapi jika nomor resi masih kosong. | **PASS** |

#### 6. Checkout & Manajemen Pesanan (`CheckoutOrderTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_pengguna_bisa_memproses_checkout_dengan_data_valid` | Checkout sukses membuat record di tabel `orders` & `order_items`. | **PASS** |
| `test_checkout_berhasil_membersihkan_item_dari_keranjang` | Item yang dicheckout otomatis dihapus dari cart. | **PASS** |
| `test_checkout_gagal_jika_data_penerima_kosong` | Form validasi data penerima dan alamat pengiriman. | **PASS** |
| `test_pengguna_bisa_melihat_invoice_pesanannya_sendiri` | Invoice pesanan dirender dengan rincian biaya lengkap. | **PASS** |
| `test_pengguna_tidak_bisa_melihat_pesanan_milik_orang_lain_idor` | HTTP 403 Forbidden jika mengakses invoice order user lain. | **PASS** |
| `test_pengguna_bisa_membatalkan_pesanan_unpaid` | Order yang belum dibayar dapat dibatalkan oleh pembeli. | **PASS** |
| `test_pengguna_tidak_bisa_membatalkan_pesanan_yang_sudah_dibayar` | Order berstatus lunas tidak dapat dibatalkan sepihak. | **PASS** |
| `test_pengguna_bisa_konfirmasi_terima_pesanan_jika_shipped_dan_paid` | Pembeli dapat menandai pesanan selesai setelah diterima. | **PASS** |
| `test_konfirmasi_terima_pesanan_gagal_jika_belum_dikirim_atau_belum_lunas` | Validasi status sebelum konfirmasi penerimaan barang. | **PASS** |

#### 7. Editor Custom Design Fabric.js (`CustomDesignTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_pengguna_bisa_mengakses_halaman_customizer` | Halaman editor 3D/Fabric.js dapat diakses publik. | **PASS** |
| `test_pengguna_bisa_menyimpan_desain_baru` | Simpan payload JSON desain dan base64 thumbnail ke storage. | **PASS** |
| `test_simpan_desain_gagal_jika_payload_tidak_lengkap` | Validasi kelengkapan nama, JSON canvas, dan thumbnail. | **PASS** |
| `test_pengguna_bisa_memperbarui_desain_miliknya` | Update nama dan konfigurasi layer desain. | **PASS** |
| `test_pengguna_tidak_bisa_mengubah_atau_menghapus_desain_orang_lain` | Pencegahan modifikasi desain milik pengguna lain. | **PASS** |
| `test_pengguna_bisa_menghapus_desain_miliknya` | Desain dihapus dari database. | **PASS** |

#### 8. Chatbot AI Gemini NLP & Live Chat (`ChatbotTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_chatbot_merespons_pertanyaan_pengguna_dengan_mock_gemini` | Mock respon Gemini API mengembalikan jawaban informatif. | **PASS** |
| `test_chatbot_memicu_handover_dan_membuat_sesi_live_chat` | Keyword eskalasi memicu flag handover dan sesi CS manusia. | **PASS** |
| `test_mode_live_chat_aktif_menyimpan_pesan_ke_database` | Percakapan saat live chat disimpan di tabel `live_chat_messages`. | **PASS** |
| `test_pengguna_bisa_polling_pesan_dan_otomatis_tandai_terbaca` | Polling asinkron pesan baru dan auto mark-as-read. | **PASS** |
| `test_kirim_pesan_kosong_ke_chatbot_ditolak` | Validasi error jika pesan kosong. | **PASS** |
| `test_penanganan_error_fallback_jika_api_gemini_mengalami_gangguan` | Fallback pesan ramah jika API Gemini timeout / quota error. | **PASS** |

#### 9. Otorisasi Filament Admin Panels (`FilamentAdminTest.php`)
| Test Method | Skenario | Status |
|---|---|:---:|
| `test_admin_bisa_mengakses_panel_admin` | Role `Admin` diizinkan masuk ke panel `/admin`. | **PASS** |
| `test_admin_bisa_mengakses_halaman_manajemen_pesanan` | Role `Admin` diizinkan membuka `/admin/orders`. | **PASS** |
| `test_tim_produksi_bisa_mengakses_panel_produksi` | Role `Tim Produksi` diizinkan masuk ke `/produksi`. | **PASS** |
| `test_owner_bisa_mengakses_panel_owner` | Role `Management/Owner` diizinkan masuk ke `/owner`. | **PASS** |
| `test_pelanggan_biasa_dilarang_mengakses_panel_admin` | Pelanggan biasa ditolak dengan status HTTP 403 Forbidden. | **PASS** |
| `test_pelanggan_biasa_dilarang_mengakses_panel_produksi` | Pelanggan biasa ditolak masuk ke panel produksi (403). | **PASS** |
| `test_pelanggan_biasa_dilarang_mengakses_panel_owner` | Pelanggan biasa ditolak masuk ke panel manajemen (403). | **PASS** |

---

## 3. Log Temuan & Catatan Robustness (Bug / Optimization Log)

| ID Temuan | Severity | Lokasi File | Deskripsi & Penanganan |
|---|:---:|---|---|
| **BUG-LOG-01** | **Medium** | [CheckoutController.php](file:///d:/laragon/www/becks-apparel-deploy/app/Http/Controllers/CheckoutController.php#L187) | **Kebutuhan Seeder Role Spatie saat Checkout**: Saat database di-refresh atau di-setup pertama kali, `CheckoutController` memanggil `User::role(['Admin', 'Tim Produksi'])->get()`. Jika database belum di-seed, Spatie melemparkan exception `RoleDoesNotExist`. <br>✅ *Solusi diuji*: Dijalankan `RoleSeeder` pada proses setup agar peran sistem selalu tersedia. |
| **BUG-LOG-02** | **Low** | [Package.php](file:///d:/laragon/www/becks-apparel-deploy/app/Models/Package.php) | **Route Model Binding Menggunakan Slug**: Model `Package` meng-override `getRouteKeyName()` ke kolom `slug`. Pemanggilan rute seperti `POST /cart/add/{package}` harus mengirimkan string slug (`paket-c-full-printing`) alih-alih numeric ID agar tidak memicu 404. |
| **BUG-LOG-03** | **Low** | `database/migrations` | **Batasan Enum Database**: Kolom kategori pada tabel `packages` hanya menerima `['jersey', 'jacket', 'tshirt', 'kemeja', 'other']` (bukan `'jaket'`). Begitu pula tabel `materials` yang menggunakan kategori `['Standard', 'Premium']` (dengan kapitalisasi). Seluruh model factory telah disesuaikan agar selalu menghasilkan data yang valid. |
| **BUG-LOG-04** | **Info** | [ShippingController.php](file:///d:/laragon/www/becks-apparel-deploy/app/Http/Controllers/ShippingController.php#L264) | **Fallback Token Biteship & RajaOngkir**: Method tracking resi memeriksa `BITESHIP_API_KEY`. Jika key kosong, controller menggunakan fallback history lokal dengan status `IN_TRANSIT` tanpa melempar fatal error ke pelanggan. |

---

## 4. Panduan Menjalankan Automated Testing

Pastikan service MySQL di Laragon telah berjalan pada port 3306.

### A. Menjalankan Seluruh Test Suite (Full Test Suite)
Gunakan binary PHP dari Laragon untuk mengeksekusi test runner:
```powershell
& "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit
```

### B. Menjalankan Unit Tests Saja
```powershell
& "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Unit
```

### C. Menjalankan Feature Tests Saja
```powershell
& "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature
```

### D. Menjalankan Modul Tertentu
- **Autentikasi**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/AuthTest.php
  ```
- **Katalog & Produk**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/ProductTest.php
  ```
- **Keranjang Belanja**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/CartTest.php
  ```
- **Alamat Tersimpan**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/AddressTest.php
  ```
- **Ongkos Kirim & Resi**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/ShippingTest.php
  ```
- **Checkout & Pesanan**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/CheckoutOrderTest.php
  ```
- **Custom Design (Fabric.js)**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/CustomDesignTest.php
  ```
- **Chatbot AI & Live Chat**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/ChatbotTest.php
  ```
- **Filament Admin Panel**:
  ```powershell
  & "D:\laragon\bin\php\php-8.3.19-Win32-vs16-x64\php.exe" vendor/phpunit/phpunit/phpunit tests/Feature/FilamentAdminTest.php
  ```

---

## 5. Integrasi Postman Collection & Environment

Koleksi Postman v2.1 telah disiapkan untuk pengujian manual maupun integrasi API di folder `postman/`:
1. [becksapparel.postman_collection.json](file:///d:/laragon/www/becks-apparel-deploy/postman/becksapparel.postman_collection.json)
2. [becksapparel.postman_environment.json](file:///d:/laragon/www/becks-apparel-deploy/postman/becksapparel.postman_environment.json)

### Cara Import ke Postman:
1. Buka aplikasi **Postman**.
2. Klik tombol **Import** di pojok kiri atas.
3. Pilih file `postman/becksapparel.postman_collection.json` dan `postman/becksapparel.postman_environment.json`.
4. Pilih environment **Becks Apparel Local Environment**.
5. Jalankan request secara berurutan atau gunakan **Postman Collection Runner** untuk mengeksekusi seluruh pengujian secara otomatis.
