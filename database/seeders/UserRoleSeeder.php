<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@becks.com'],
            [
                'name' => 'Super Admin Becks',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Admin');

        // 2. Create Produksi
        $produksi = User::firstOrCreate(
            ['email' => 'produksi@becks.com'],
            [
                'name' => 'Tim Workshop',
                'password' => Hash::make('password'),
            ]
        );
        $produksi->assignRole('Tim Produksi');

        // 3. Create Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@becks.com'],
            [
                'name' => 'Owner Becks',
                'password' => Hash::make('password'),
            ]
        );
        $owner->assignRole('Management/Owner');

        // 4. Create Pelanggan
        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Budi Pelanggan',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('Pelanggan');
    }
}
