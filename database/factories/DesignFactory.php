<?php

namespace Database\Factories;

use App\Models\Design;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignFactory extends Factory
{
    protected $model = Design::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => 'Desain Jersey ' . fake()->words(2, true),
            'design_json' => [
                'model' => 'v-neck-short',
                'parts' => [
                    'body' => '#ffffff',
                    'collar' => '#000000',
                ],
                'elements' => []
            ],
            'preview_path' => 'designs/sample_preview.jpg',
        ];
    }
}
