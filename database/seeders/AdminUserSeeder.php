<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::firstOrCreate(
            ['email' => 'admin@morna.com'],
            [
                'name'              => 'Morna Admin',
                'password'          => Hash::make('password123'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Normal user account
        User::firstOrCreate(
            ['email' => 'user@morna.com'],
            [
                'name'              => 'Morna User',
                'password'          => Hash::make('password123'),
                'role'              => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}