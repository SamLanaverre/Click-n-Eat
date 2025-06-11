<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class GlobalCategoriesAndItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider les tables pivot et les tables principales pour éviter les duplications
        DB::table('restaurant_item')->truncate();
        DB::table('category_item')->truncate();
        DB::table('items')->truncate();
        DB::table('categories')->truncate();
        
        // Créer des catégories globales
        $categories = [
            ['name' => 'Entrées'],
            ['name' => 'Plats principaux'],
            ['name' => 'Desserts'],
            ['name' => 'Boissons'],
            ['name' => 'Pizzas'],
            ['name' => 'Burgers'],
            ['name' => 'Salades'],
            ['name' => 'Pâtes'],
            ['name' => 'Sushis'],
            ['name' => 'Végétarien'],
        ];
        
        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
        
        // Créer des items globaux avec descriptions
        $items = [
            // Entrées
            [
                'name' => 'Salade César',
                'description' => 'Laitue romaine, croûtons, parmesan, sauce César maison',
                'categories' => ['Entrées', 'Salades'],
            ],
            [
                'name' => 'Bruschetta',
                'description' => 'Pain grillé à l\'ail avec tomates, basilic et huile d\'olive',
                'categories' => ['Entrées'],
            ],
            [
                'name' => 'Soupe à l\'oignon',
                'description' => 'Soupe à l\'oignon gratinée avec croûtons et fromage',
                'categories' => ['Entrées'],
            ],
            
            // Plats principaux
            [
                'name' => 'Steak frites',
                'description' => 'Steak de bœuf grillé servi avec des frites maison',
                'categories' => ['Plats principaux'],
            ],
            [
                'name' => 'Poulet rôti',
                'description' => 'Poulet fermier rôti aux herbes de Provence',
                'categories' => ['Plats principaux'],
            ],
            
            // Desserts
            [
                'name' => 'Tiramisu',
                'description' => 'Dessert italien au mascarpone et café',
                'categories' => ['Desserts'],
            ],
            [
                'name' => 'Crème brûlée',
                'description' => 'Crème à la vanille avec une couche de sucre caramélisé',
                'categories' => ['Desserts'],
            ],
            
            // Boissons
            [
                'name' => 'Coca-Cola',
                'description' => 'Soda rafraîchissant',
                'categories' => ['Boissons'],
            ],
            [
                'name' => 'Eau minérale',
                'description' => 'Eau minérale naturelle',
                'categories' => ['Boissons'],
            ],
            
            // Pizzas
            [
                'name' => 'Pizza Margherita',
                'description' => 'Sauce tomate, mozzarella, basilic frais',
                'categories' => ['Pizzas', 'Plats principaux'],
            ],
            [
                'name' => 'Pizza Quatre Fromages',
                'description' => 'Mozzarella, gorgonzola, parmesan, chèvre',
                'categories' => ['Pizzas', 'Plats principaux'],
            ],
            
            // Burgers
            [
                'name' => 'Cheeseburger',
                'description' => 'Steak haché, cheddar, salade, tomate, oignon, sauce burger',
                'categories' => ['Burgers', 'Plats principaux'],
            ],
            [
                'name' => 'Burger Végétarien',
                'description' => 'Galette de légumes, fromage de chèvre, roquette, sauce au yaourt',
                'categories' => ['Burgers', 'Plats principaux', 'Végétarien'],
            ],
            
            // Pâtes
            [
                'name' => 'Spaghetti Bolognaise',
                'description' => 'Spaghetti avec sauce bolognaise maison',
                'categories' => ['Pâtes', 'Plats principaux'],
            ],
            [
                'name' => 'Penne Arrabiata',
                'description' => 'Penne avec sauce tomate épicée et ail',
                'categories' => ['Pâtes', 'Plats principaux', 'Végétarien'],
            ],
            
            // Sushis
            [
                'name' => 'Maki Saumon',
                'description' => '6 pièces de maki au saumon frais',
                'categories' => ['Sushis', 'Plats principaux'],
            ],
            [
                'name' => 'California Roll',
                'description' => '6 pièces de california roll au crabe, avocat et concombre',
                'categories' => ['Sushis', 'Plats principaux'],
            ],
        ];
        
        // Récupérer toutes les catégories pour les associer aux items
        $categoriesMap = Category::all()->pluck('id', 'name')->toArray();
        
        // Créer les items et leurs associations avec les catégories
        foreach ($items as $itemData) {
            $item = Item::create([
                'name' => $itemData['name'],
                'description' => $itemData['description'],
            ]);
            
            // Associer les catégories à l'item
            foreach ($itemData['categories'] as $categoryName) {
                if (isset($categoriesMap[$categoryName])) {
                    $item->categories()->attach($categoriesMap[$categoryName]);
                }
            }
        }
        
        // Associer des items aux restaurants existants avec des prix et disponibilités aléatoires
        $restaurants = Restaurant::all();
        $items = Item::all();
        
        foreach ($restaurants as $restaurant) {
            // Sélectionner aléatoirement entre 5 et 15 items pour ce restaurant
            $randomItems = $items->random(rand(5, min(15, $items->count())));
            
            foreach ($randomItems as $item) {
                // Vérifier si la relation existe déjà pour éviter les doublons
                if (!DB::table('restaurant_item')->where('restaurant_id', $restaurant->id)->where('item_id', $item->id)->exists()) {
                    DB::table('restaurant_item')->insert([
                        'restaurant_id' => $restaurant->id,
                        'item_id' => $item->id,
                        'price' => rand(5, 25) + 0.99, // Prix aléatoire entre 5.99€ et 25.99€
                        'is_active' => rand(0, 10) > 1, // 90% de chance d'être actif
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
        
        $this->command->info('Catégories et items globaux créés avec succès!');
    }
}
