<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Material;
use App\Models\Upgrade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = base_path('Master Data Becks Apparel.csv');
        if (!file_exists($filePath)) {
            return;
        }

        $lines = file($filePath);
        $data = array_map('str_getcsv', $lines);

        // 1. Seed Packages (Lines 5-15)
        for ($i = 4; $i <= 14; $i++) {
            if (!isset($data[$i])) continue;
            
            $row = $data[$i];
            if (empty($row[0])) continue;

            Package::updateOrCreate(
                ['slug' => Str::slug($row[0] . '-' . $row[1])],
                [
                    'category' => strtolower($row[0]),
                    'name' => $row[1],
                    'base_price' => (float) str_replace(['Rp', '.', ' '], '', $row[2]),
                    'specification' => $row[3] ?? null,
                    'is_active' => true,
                ]
            );
        }

        // 2. Seed Upgrades (Lines 19-27)
        for ($i = 18; $i <= 26; $i++) {
            if (!isset($data[$i])) continue;
            
            $row = $data[$i];
            if (empty($row[0])) continue;

            Upgrade::updateOrCreate(
                ['name' => $row[0]],
                [
                    'category' => $row[1],
                    'price' => (float) str_replace(['Rp', '.', ' '], '', $row[2]),
                    'description' => $row[3] ?? null,
                ]
            );
        }

        // 3. Seed Materials (Lines 37-56)
        for ($i = 36; $i <= 55; $i++) {
            if (!isset($data[$i])) continue;
            
            $row = $data[$i];
            if (empty($row[0])) continue;

            Material::updateOrCreate(
                ['name' => $row[0]],
                [
                    'category' => $row[1] ?? 'Standard',
                    'status' => ($row[2] ?? 'Ready') === 'Ready' ? 'Ready' : 'Out of Stock',
                    'additional_price' => 0, // Default 0 as per CSV for now
                    'allowed_categories' => ['jersey'], // Default to jersey for these fabrics
                ]
            );
        }
    }
}
