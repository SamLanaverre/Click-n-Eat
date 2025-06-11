<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\User;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Trouver un utilisateur admin ou restaurateur, ou créer un utilisateur restaurateur si nécessaire
        $owner = User::where('role', 'restaurateur')->first();
        
        if (!$owner) {
            $owner = User::where('role', 'admin')->first();
        }
        
        if (!$owner) {
            $owner = User::create([
                'name' => 'Restaurateur Test',
                'email' => 'resto@example.com',
                'password' => bcrypt('password'),
                'role' => 'restaurateur',
            ]);
        }
        
        // Créer un restaurant de test
        Restaurant::create([
            'name' => 'Restaurant Test',
            'owner_id' => $owner->id,
            'description' => 'Ceci est un restaurant de test pour vérifier l\'affichage de l\'interface d\'administration.',
            'address' => '123 Rue de Test, 75000 Paris',
            'phone' => '01 23 45 67 89',
            'email' => 'contact@resto-test.com',
            'is_active' => true,
            'opening_hours' => [
                'lundi' => '11h-15h, 18h-22h',
                'mardi' => '11h-15h, 18h-22h',
                'mercredi' => '11h-15h, 18h-22h',
                'jeudi' => '11h-15h, 18h-22h',
                'vendredi' => '11h-15h, 18h-23h',
                'samedi' => '11h-23h',
                'dimanche' => '11h-22h'
            ]
        ]);
    }
}
