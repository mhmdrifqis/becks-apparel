<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            // JERSEY PACKAGES
            [
                'category' => 'jersey',
                'name' => 'Paket A - Standard',
                'slug' => 'paket-a-standard',
                'base_price' => 90000,
                'description' => 'Pilihan ekonomis untuk tim Anda. Kualitas bahan tetap premium dengan teknik cetak DTF.',
                'features' => ['Non-printing (Bahan Warna Solid)', 'Logo DTF', 'Nameset DTF', 'Bahan Dry-Fit Premium'],
                'image_path' => 'assets/images/packages/standard_jersey.png'
            ],
            [
                'category' => 'jersey',
                'name' => 'Paket B - Sleeve Print',
                'slug' => 'paket-b-sleeve-print',
                'base_price' => 110000,
                'description' => 'Sentuhan desain di lengan untuk tampilan yang lebih dinamis.',
                'features' => ['Lengan Full Printing', 'Badan Depan/Belakang Non-printing', 'Logo DTF', 'Nameset DTF'],
                'image_path' => 'assets/images/packages/standard_jersey.png'
            ],
            [
                'category' => 'jersey',
                'name' => 'Paket C - Front Print',
                'slug' => 'paket-c-front-print',
                'base_price' => 130000,
                'description' => 'Visual maksimal di bagian badan jersey dengan celana solid yang elegan.',
                'features' => ['Jersey Full Printing', 'Celana Non-printing', 'Logo Full Print/DTF', 'Nameset Full Print/DTF'],
                'image_path' => 'assets/images/packages/pro_jersey.png'
            ],
            [
                'category' => 'jersey',
                'name' => 'Paket D - Full Printing',
                'slug' => 'paket-d-full-printing',
                'base_price' => 160000,
                'description' => 'Tampilan profesional menyeluruh dari jersey hingga celana.',
                'features' => ['Jersey Full Printing', 'Celana Full Printing', 'Kebebasan Desain Tak Terbatas', 'Bahan High-Performance'],
                'image_path' => 'assets/images/packages/pro_jersey.png'
            ],
            [
                'category' => 'jersey',
                'name' => 'Paket E - Professional',
                'slug' => 'paket-e-professional',
                'base_price' => 170000,
                'description' => 'Paket lengkap dengan detail sponsor dan logo berkualitas tinggi.',
                'features' => ['Full Printing Jersey & Celana', 'Detail Logo/Sponsor DTF Premium', 'Exclusive Finish', 'Cocok untuk Kompetisi'],
                'image_path' => 'assets/images/packages/pro_jersey.png'
            ],

            // JACKET PACKAGES
            [
                'category' => 'jacket',
                'name' => 'Jacket Full Printing',
                'slug' => 'jacket-full-printing',
                'base_price' => 170000,
                'description' => 'Jaket dengan grafis tajam di seluruh bagian.',
                'features' => ['Sublimation Full Printing', 'Bahan Lotto/Parachute Premium', 'Resleting Kualitas Ekspor', 'Saku Kiri-Kanan'],
                'image_path' => 'assets/images/packages/premium_jacket.png'
            ],
            [
                'category' => 'jacket',
                'name' => 'Jacket Combination',
                'slug' => 'jacket-combination',
                'base_price' => 155000,
                'description' => 'Kombinasi bahan solid dengan aksen grafis printing.',
                'features' => ['Aksen Printing di Bagian Tertentu', 'Bahan Kombinasi Premium', 'Desain Semi-Custom', 'Tahan Lama'],
                'image_path' => 'assets/images/packages/premium_jacket.png'
            ],
            [
                'category' => 'jacket',
                'name' => 'Pro Jacket Set',
                'slug' => 'pro-jacket-set',
                'base_price' => 250000,
                'description' => 'Setelan lengkap jaket dan celana training untuk tim profesional.',
                'features' => ['Satu Set Jaket & Celana Training', 'Logo Bordir/DTF', 'Karet Pinggang Elastis', 'Full Custom Designs'],
                'image_path' => 'assets/images/packages/premium_jacket.png'
            ],

            // T-SHIRT PACKAGES
            [
                'category' => 'tshirt',
                'name' => 'T-Shirt Cotton 24s',
                'slug' => 't-shirt-cotton-24s',
                'base_price' => 80000,
                'description' => 'Bahan katun tebal dan nyaman untuk penggunaan sehari-hari.',
                'features' => ['Bahan Cotton Combed 24s', 'Sablon DTF/Screen Print', 'Tekstur Lembut', 'Menyerap Keringat'],
                'image_path' => 'assets/images/packages/cotton_tshirt.png'
            ],
            [
                'category' => 'tshirt',
                'name' => 'T-Shirt Cotton 30s',
                'slug' => 't-shirt-cotton-30s',
                'base_price' => 60000,
                'description' => 'Bahan katun yang lebih tipis dan adem, sangat populer.',
                'features' => ['Bahan Cotton Combed 30s', 'Sablon DTF/Screen Print', 'Warna Awet', 'Jahitan Rantai'],
                'image_path' => 'assets/images/packages/cotton_tshirt.png'
            ],
        ];

        foreach ($packages as $package) {
            \App\Models\Package::create($package);
        }
    }
}
