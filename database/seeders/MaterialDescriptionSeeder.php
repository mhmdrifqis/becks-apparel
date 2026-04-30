<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialsData = [
            'Milano' => 'Bahan jersey dengan motif zig-zag atau pori-pori menyerupai sisik. Sangat menyerap keringat, lentur, dan memberikan sirkulasi udara yang baik saat berolahraga.',
            'Benzema' => 'Memiliki tekstur pori-pori diagonal/segi enam. Sangat ringan, halus, dan nyaman dipakai untuk aktivitas dengan intensitas tinggi.',
            'Smash' => 'Karakteristiknya halus, melar, dan pori-porinya rapi. Memberikan kesan jatuh dan pas di badan.',
            'N-Tech' => 'Material dengan teknologi anti-bakteri dan quick-dry (cepat kering). Sangat premium untuk jersey profesional.',
            'Wafel' => 'Permukaannya bertekstur kotak-kotak seperti kue wafel. Lumayan tebal namun tetap adem dan tidak mudah kusut.',
            'MU' => 'Bahan licin, mengkilap, dan memiliki serat rapat. Memberikan kesan elegan saat dipakai.',
            'Kultus' => 'Kain yang cukup lembut dengan sedikit corak di permukaannya. Nyaman untuk penggunaan sehari-hari maupun olahraga santai.',
            'Thailand' => 'Sering disebut bahan dry-fit Thailand, permukaannya halus, sangat elastis, dan menyerap keringat dengan sempurna.',
            'Bubin' => 'Bahan tebal dengan rongga pori-pori besar, sirkulasi udara sangat lancar. Biasa digunakan untuk jersey basket.',
            'Adidas' => 'Serat kainnya tebal namun bagian dalamnya tidak berbulu. Biasa digunakan untuk celana olahraga atau jaket training.',
            'Diamond' => 'Teksturnya terlihat seperti permata/berlian kecil. Tahan lama, tebal, dan memberikan kesan mewah pada jersey.',
            'Holland' => 'Tekstur bahan yang khas, tebal namun tidak kaku. Cocok untuk jersey e-sport atau voli.',
            'Koba' => 'Bahan premium dengan permukaan lembut dan sangat nyaman bersentuhan dengan kulit.',
            'Virtual' => 'Material modern yang dikembangkan khusus untuk printing sublimasi dengan hasil warna yang sangat tajam dan awet.',
            'Bambu' => 'Terbuat dari serat bambu alami. Anti-bau, sangat lembut, dan eco-friendly.',
            'Brazil' => 'Bahan yang mengkilap dan jatuh. Cocok untuk jersey sepak bola dengan look yang elegan.',
            'Ferari' => 'Bahan yang cukup ringan, licin, dan memantulkan sedikit cahaya. Memberikan kesan sporty yang kuat.',
            'England' => 'Mirip bahan wafel namun dengan pola yang lebih kecil. Awet dan tahan terhadap gesekan.',
            'Sena' => 'Kain dengan pori-pori bulat yang padat. Menyerap keringat maksimal dan mudah dicuci.',
            'Tepo' => 'Sangat lentur, jatuh, dan memiliki serat yang rapat, memberikan sensasi dingin di kulit.',
        ];

        foreach ($materialsData as $name => $desc) {
            // Update jika bahan sudah ada
            $material = Material::where('name', 'LIKE', '%' . $name . '%')->first();
            if ($material) {
                // Generate path foto: assets/images/materials/milano.jpg dst.
                // Anda bisa mengganti formatnya (png/jpg) sesuai kebutuhan file Anda nanti.
                $slug = strtolower(str_replace(' ', '-', $name));
                $material->update([
                    'description' => $desc,
                    'image_path' => 'assets/images/materials/' . $slug . '.jpg'
                ]);
            }
        }
    }
}
