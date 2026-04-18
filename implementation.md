# BECKS APPAREL - IMPLEMENTATION GUIDE

## 1. Core Tech Stack
* **Backend**: Laravel 11.
* **Frontend**: React.js, Tailwind CSS, Inertia.js (Monolith Approach).
* **Autentikasi**: Laravel Breeze + Laravel Socialite (Google Login).
* **Dashboard Admin**: Filament PHP + Spatie Permission.
* **Real-time**: Laravel Reverb (Server) + Laravel Echo (Client).
* **Queues**: Laravel Horizon (Handling PDF Invoice & Fonnte WhatsApp API).

## 2. Database Schema (High Level)
* `packages`: id, category, name, base_price, description.
* `materials`: id, name, category (standard/premium), additional_price.
* `upgrades`: id, name, type, price.
* `designs`: id, user_id, design_json, preview_path.
* `orders`: id, user_id, order_number, total_amount, deposit_amount, status, payment_token.
* `order_items`: id, order_id, package_id, material_id, design_id, size, size_surcharge.

## 3. Fabric.js Workflow
* Kanvas kustomisasi harus diekstrak menjadi format **JSON** menggunakan `canvas.toJSON()`.
* Data JSON disimpan di kolom `design_json` pada tabel `designs` agar bisa di-render ulang oleh Tim Produksi.
* Generate thumbnail Base64 saat simpan desain untuk keperluan preview cepat di keranjang.

## 4. Pricing Logic Formula
Setiap penambahan item ke keranjang harus dihitung secara asinkron di backend:
$$Total = PackageBase + MaterialExtra + \sum UpgradePrice + SizeSurcharge$$

## 5. Integrasi Pihak Ketiga
* **Midtrans**: Gunakan Webhook untuk mengubah status order menjadi "Paid" secara otomatis.
* **Fonnte**: Kirim notifikasi WhatsApp beserta link PDF Invoice saat status berubah menjadi "Paid".
* **Google**: Gunakan Socialite untuk pendaftaran pelanggan instan.

## 6. Real-time Notification
* Gunakan `PrivateChannel` di Laravel Echo untuk mengirim pembaruan status produksi secara eksklusif ke pelanggan yang bersangkutan.
* Admin mendapatkan push notification via Filament saat ada transaksi baru yang lunas.
