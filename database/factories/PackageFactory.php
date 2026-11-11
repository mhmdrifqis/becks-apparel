<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        $name = 'Paket Jersey ' . fake()->unique()->words(2, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(['jersey', 'jacket', 'tshirt', 'kemeja', 'other']),
            'description' => fake()->paragraph(),
            'base_price' => fake()->numberBetween(80000, 200000),
            'weight' => 250,
            'images' => ['packages/sample1.jpg', 'packages/sample2.jpg'],
            'is_active' => true,
        ];
    }
}
