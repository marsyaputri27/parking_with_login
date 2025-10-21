<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['username' => 'admin.test'],
            [
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'active' => true,
            ]
        );

        // Kasir
        User::updateOrCreate(
            ['username' => 'kasir.test'],
            [
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
                'active' => true,
            ]
        );
    }
}
