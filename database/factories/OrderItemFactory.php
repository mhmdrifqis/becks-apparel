<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Package;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $qty = 2;
        $roster = [
            ['name' => 'Player 1', 'number' => '10', 'size' => 'L', 'upgrades' => [], 'surcharge' => 0],
            ['name' => 'Player 2', 'number' => '11', 'size' => 'XXL', 'upgrades' => [], 'surcharge' => 5000],
        ];

        return [
            'order_id' => Order::factory(),
            'package_id' => Package::factory(),
            'material_id' => Material::factory(),
            'quantity' => $qty,
            'roster' => $roster,
            'size_surcharge' => 5000,
            'subtotal' => 265000,
            'design_id' => null,
            'material_usage' => 0,
        ];
    }
}
