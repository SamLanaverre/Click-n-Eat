<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Création du compte admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ]);

        // Création du compte restaurateur
        User::create([
            'name' => 'Restaurant',
            'email' => 'restaurant@mail.fr',
            'password' => Hash::make('restaurant'),
            'role' => 'restaurateur'
        ]);

        // Création du compte client
        User::create([
            'name' => 'Client',
            'email' => 'client@example.fr',
            'password' => Hash::make('client'),
            'role' => 'client'
        ]);
    }
}
