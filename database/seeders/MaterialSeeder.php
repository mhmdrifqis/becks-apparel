<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Material::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // === JERSEY (76 bahan) ===
        $jerseyFabrics = [
            'WAFEL', 'MU', 'KULTUS', 'THAILAND', 'BUBIN', 'ADIDAS', 'DIAMOND',
            'HOLLAND', 'MILANO', 'KOBA', 'VIRTUAL', 'BAMBU', 'BRAZIL', 'FERARI',
            'ENGLAND', 'SENA', 'BENZEMA', 'TERO', 'RANTAI POLI', 'SUPERIOR',
            'JALA ERBIN', 'SARANG TAWON', 'CIRCLE', 'POLYTEXTURE', 'ANDROMAX BESAR',
            'BILABONG', 'BUGATI', 'WAFEL MINI', 'ALFINA', 'SKIN BREATHER', 'DROPNEEDLE',
            'BRICK', 'KULIT JERUK', 'WHEELTRACK', 'KOTAK SIDO', 'BILIK', 'MILENIAL',
            'WAVE', 'AIRWALK', 'TRICOT TRACK', 'AIRWALK SALUR', 'MESSY', 'DRAGON',
            'PUMA', 'ITALIC', 'TRICOT SQUARE', 'TRICOT SIDO', 'PIRAMID', 'SMASH',
            'ARUMBI', 'LOVINA', 'MILANO SALUR', 'CHEVRON', 'CASABLANCA', 'CATBURY',
            'JALA AERO', 'JALA METAFORA', 'CORDOBA', 'MONTANA', 'STRIPE', 'MOSSES',
            'BENIK', 'DINAMIC', 'ZOYA', 'HOLLOW', 'ERBINA', 'EVERTONE', 'DRAXTER',
            'DOTMATRIX', 'WADIMOR', 'DRAGA', 'NEWCASTLE', 'ELITLINE', 'CROCODILE',
            'LACOSTE TAIWAN', 'GEMINI',
        ];

        foreach ($jerseyFabrics as $name) {
            Material::create([
                'name'          => $name,
                'category'      => 'Standard',
                'status'        => 'Ready',
                'additional_price' => 0,
                'stock'         => 999,
                'unit'          => 'Meter',
                'product_types' => ['jersey'],
            ]);
        }

        // === JAKET (2 bahan) ===
        $jaketFabrics = ['LOTTO', 'DIADORA'];
        foreach ($jaketFabrics as $name) {
            Material::create([
                'name'          => $name,
                'category'      => 'Standard',
                'status'        => 'Ready',
                'additional_price' => 0,
                'stock'         => 999,
                'unit'          => 'Meter',
                'product_types' => ['jacket'],
            ]);
        }

        // === KAOS / T-SHIRT (2 bahan) ===
        $kaosFabrics = [
            'COTTON COMBED 24S',
            'COTTON COMBED 30S',
        ];
        foreach ($kaosFabrics as $name) {
            Material::create([
                'name'          => $name,
                'category'      => 'Standard',
                'status'        => 'Ready',
                'additional_price' => 0,
                'stock'         => 999,
                'unit'          => 'Meter',
                'product_types' => ['tshirt'],
            ]);
        }

        // === KEMEJA (2 bahan) ===
        $kemejeFabrics = [
            'VERLANDO CP',
            'MARYLAND DRILL',
        ];
        foreach ($kemejeFabrics as $name) {
            Material::create([
                'name'          => $name,
                'category'      => 'Standard',
                'status'        => 'Ready',
                'additional_price' => 0,
                'stock'         => 999,
                'unit'          => 'Meter',
                'product_types' => ['kemeja'],
            ]);
        }

        $this->command->info('MaterialSeeder: ' . Material::count() . ' bahan berhasil ditambahkan.');
    }
}
