<?php

namespace Database\Factories;

use App\Models\Upgrade;
use Illuminate\Database\Eloquent\Factories\Factory;

class UpgradeFactory extends Factory
{
    protected $model = Upgrade::class;

    public function definition(): array
    {
        return [
            'name' => 'Upgrade ' . fake()->unique()->words(2, true),
            'category' => fake()->randomElement(['Lengan', 'Logo', 'Kerah', 'Bahan']),
            'price' => fake()->randomElement([10000, 15000, 20000, 25000]),
        ];
    }
}
