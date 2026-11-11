<?php

namespace Database\Factories;

use App\Models\ReturnRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReturnRequestFactory extends Factory
{
    protected $model = ReturnRequest::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'user_id' => User::factory(),
            'reason' => 'Terdapat cacat jahitan pada bagian kerah.',
            'proof_images' => ['returns/proof1.jpg'],
            'status' => 'pending',
            'admin_notes' => null,
        ];
    }
}
