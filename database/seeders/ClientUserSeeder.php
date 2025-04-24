<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Client',
            'email' => 'client@example.fr',
            'password' => Hash::make('client'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);
    }
}