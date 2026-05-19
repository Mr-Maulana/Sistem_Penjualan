<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() > 0) {
            return;
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }
}

