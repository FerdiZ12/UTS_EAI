<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        Users::create([
            'name' => 'Canggih',
            'email' => 'canggih@example.com',
            'password' => Hash::make('password123'),
        ]);

        Users::create([
            'name' => 'Arya',
            'email' => 'arya@example.com',
            'password' => Hash::make('password123'),
        ]);

        Users::create([
            'name' => 'Varhan',
            'email' => 'varhan@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
