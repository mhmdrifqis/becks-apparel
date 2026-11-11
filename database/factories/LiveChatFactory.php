<?php

namespace Database\Factories;

use App\Models\LiveChat;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiveChatFactory extends Factory
{
    protected $model = LiveChat::class;

    public function definition(): array
    {
        return [
            'user_id' => 'guest_' . fake()->uuid(),
            'user_name' => fake()->name(),
            'status' => 'active',
        ];
    }
}
