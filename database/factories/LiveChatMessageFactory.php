<?php

namespace Database\Factories;

use App\Models\LiveChatMessage;
use App\Models\LiveChat;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiveChatMessageFactory extends Factory
{
    protected $model = LiveChatMessage::class;

    public function definition(): array
    {
        return [
            'live_chat_id' => LiveChat::factory(),
            'sender' => fake()->randomElement(['user', 'admin']),
            'message' => fake()->sentence(),
            'is_read' => false,
        ];
    }
}
