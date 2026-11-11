<?php

namespace Database\Factories;

use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => 'Rumah',
            'nama_penerima' => fake()->name(),
            'no_telepon' => '08' . fake()->numerify('##########'),
            'alamat_lengkap' => fake()->streetAddress(),
            'kota' => 'Tangerang',
            'provinsi' => 'Banten',
            'kode_pos' => '15111',
            'rajaongkir_city_id' => '456',
            'rajaongkir_province_id' => '3',
            'is_default' => false,
        ];
    }
}
