<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ClientUserSeeder::class,
            RestaurantSeeder::class,
        ]);
        
        // Crée 12 restaurants
        Restaurant::factory(12)->create();

        // Utiliser le nouveau seeder pour les catégories et items globaux
        // Commentez cette ligne si vous souhaitez conserver les anciennes données
        $this->call(GlobalCategoriesAndItemsSeeder::class);
    }
}