<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'name' => 'Bahan ' . fake()->unique()->word(),
            'category' => fake()->randomElement(['Standard', 'Premium']),
            'stock' => fake()->numberBetween(10, 500),
            'unit' => 'meter',
            'additional_price' => fake()->randomElement([0, 5000, 10000, 15000]),
            'image_path' => 'materials/sample.jpg',
            'description' => fake()->sentence(),
            'product_types' => ['jersey', 'tshirt', 'jaket'],
        ];
    }
}
