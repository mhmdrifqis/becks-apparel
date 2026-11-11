<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\User;
use App\Models\Package;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'package_id' => Package::factory(),
            'material_id' => Material::factory(),
            'quantity' => fake()->numberBetween(1, 12),
        ];
    }
}
