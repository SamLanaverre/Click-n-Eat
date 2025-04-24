<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Item;
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
        ]);
        
        // Crée 12 restaurants
        Restaurant::factory(12)->create();

        // Crée 12 catégories
        Category::factory(12)->create();

        // Crée 10 items (avec des catégories existantes)
        Item::factory(10)->create(); // Ajoute 10 items
    }
}