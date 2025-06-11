<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Auth\Middleware\Authenticate;

class DashboardController extends Controller
{
    #[Authenticate]
    public function index(): View
    {
        // Récupérer tous les restaurants
        $restaurants = Restaurant::all();
        
        // Pour chaque restaurant, charger manuellement les catégories et items associés
        foreach ($restaurants as $restaurant) {
            // Récupérer les items du restaurant qui sont actifs
            $restaurantItems = $restaurant->items()->where('items.is_active', true)->get();
            
            // Récupérer les catégories de ces items
            $categoryIds = [];
            foreach ($restaurantItems as $item) {
                $itemCategories = $item->categories;
                foreach ($itemCategories as $category) {
                    $categoryIds[$category->id] = $category;
                }
            }
            
            // Attacher les catégories au restaurant
            $restaurant->loadedCategories = collect($categoryIds)->values();
        }

        return view('client.dashboard', [
            'restaurants' => $restaurants
        ]);
    }
}
