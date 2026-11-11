<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $total = fake()->numberBetween(150000, 2000000);
        return [
            'user_id' => User::factory(),
            'order_number' => 'BCK-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'recipient_name' => fake()->name(),
            'recipient_phone' => '08' . fake()->numerify('##########'),
            'shipping_address' => fake()->address(),
            'shipping_cost' => fake()->randomElement([15000, 20000, 30000]),
            'shipping_service' => 'REG',
            'courier_name' => 'JNE',
            'notes' => fake()->sentence(),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total_amount' => $total,
            'deposit_amount' => 0,
            'payment_gateway_id' => null,
            'tracking_number' => null,
        ];
    }
}
