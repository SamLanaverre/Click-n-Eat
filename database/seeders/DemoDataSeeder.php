<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Item;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Comptes utilisateurs de test
        $admin = User::firstOrCreate([
            'email' => 'admin@clickneat.test',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $restaurateur = User::firstOrCreate([
            'email' => 'restaurateur@clickneat.test',
        ], [
            'name' => 'Restaurateur',
            'password' => Hash::make('resto123'),
            'role' => 'restaurateur',
        ]);

        $client = User::firstOrCreate([
            'email' => 'client@clickneat.test',
        ], [
            'name' => 'Client',
            'password' => Hash::make('client123'),
            'role' => 'client',
        ]);


        // 2. Restaurants avec catégories et items
        $restaurants = [
            [
                'name' => 'La Dolce Vita',
                'description' => 'Cuisine italienne authentique, pizzas au feu de bois et pâtes fraîches.',
                'address' => '12 rue de Rome, 75001 Paris',
                'phone' => '01 23 45 67 89',
                'owner_id' => $restaurateur->id,
                'categories' => [
                    [
                        'name' => 'Pizzas',
                        'items' => [
                            ['name' => 'Margherita', 'description' => 'Tomate, mozzarella, basilic', 'price' => 9.5],
                            ['name' => 'Quattro Formaggi', 'description' => '4 fromages italiens', 'price' => 12.0],
                        ]
                    ],
                    [
                        'name' => 'Pâtes',
                        'items' => [
                            ['name' => 'Tagliatelle Carbonara', 'description' => 'Crème, lardons, parmesan', 'price' => 11.0],
                        ]
                    ],
                ],
            ],
            [
                'name' => 'Burger House',
                'description' => 'Burgers gourmets et frites maison.',
                'address' => '22 avenue des Champs, 75008 Paris',
                'phone' => '01 98 76 54 32',
                'owner_id' => $restaurateur->id,
                'categories' => [
                    [
                        'name' => 'Burgers',
                        'items' => [
                            ['name' => 'Classic Burger', 'description' => 'Bœuf, cheddar, salade, tomate', 'price' => 10.0],
                            ['name' => 'Chicken Burger', 'description' => 'Poulet croustillant, sauce maison', 'price' => 10.5],
                        ]
                    ],
                    [
                        'name' => 'Accompagnements',
                        'items' => [
                            ['name' => 'Frites maison', 'description' => 'Frites fraîches', 'price' => 3.5],
                        ]
                    ],
                ],
            ],
            [
                'name' => 'Sushi Zen',
                'description' => 'Sushis, makis et spécialités japonaises.',
                'address' => '5 rue du Soleil Levant, 69001 Lyon',
                'phone' => '04 56 78 90 12',
                'owner_id' => $restaurateur->id,
                'categories' => [
                    [
                        'name' => 'Sushis',
                        'items' => [
                            ['name' => 'Sushi Saumon', 'description' => '2 pièces', 'price' => 4.0],
                            ['name' => 'Sushi Thon', 'description' => '2 pièces', 'price' => 4.5],
                        ]
                    ],
                    [
                        'name' => 'Makis',
                        'items' => [
                            ['name' => 'Maki Concombre', 'description' => '6 pièces', 'price' => 5.0],
                        ]
                    ],
                ],
            ],
        ];

        foreach ($restaurants as $rData) {
            $restaurant = Restaurant::create([
                'name' => $rData['name'],
                'description' => $rData['description'],
                'address' => $rData['address'],
                'phone' => $rData['phone'],
                'owner_id' => $rData['owner_id'],
                'opening_hours' => [
                    'monday' => ['11:30-14:30', '18:30-22:30'],
                    'tuesday' => ['11:30-14:30', '18:30-22:30'],
                    'wednesday' => ['11:30-14:30', '18:30-22:30'],
                    'thursday' => ['11:30-14:30', '18:30-22:30'],
                    'friday' => ['11:30-14:30', '18:30-23:00'],
                    'saturday' => ['12:00-15:00', '18:30-23:00'],
                    'sunday' => [],
                ],
            ]);

            foreach ($rData['categories'] as $cData) {
                $category = Category::create([
                    'name' => $cData['name'],
                    'restaurant_id' => $restaurant->id,
                ]);
                foreach ($cData['items'] as $iData) {
                    Item::create([
                        'name' => $iData['name'],
                        'price' => $iData['price'],
                        'category_id' => $category->id,
                    ]);
                }
            }
        }
    }
}
