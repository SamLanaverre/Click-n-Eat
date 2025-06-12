<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Middleware\Authenticate;

class MenuController extends Controller
{
    /**
     * Affiche le menu du restaurant
     */
    #[Authenticate]
    public function index(Restaurant $restaurant): View
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        // Récupérer tous les items du menu
        $menuItems = $restaurant->items()->where('is_in_menu', true)->get();
        
        // Récupérer toutes les catégories pour organiser les items par catégorie
        $categories = Category::with(['items' => function($query) use ($restaurant) {
            $query->where('restaurant_id', $restaurant->id)
                  ->where('is_in_menu', true);
        }])->get();
        
        return view('restaurant.menu.index', [
            'restaurant' => $restaurant,
            'menuItems' => $menuItems,
            'categories' => $categories
        ]);
    }

    /**
     * Ajoute un item au menu
     */
    #[Authenticate]
    public function addToMenu(Request $request, Restaurant $restaurant, Item $item): RedirectResponse
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant et que l'item appartient bien à ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }
        
        // Ajouter l'item au menu
        $item->is_in_menu = true;
        $item->save();
        
        return redirect()->back()->with('success', 'Item ajouté au menu avec succès.');
    }

    /**
     * Retire un item du menu
     */
    #[Authenticate]
    public function removeFromMenu(Restaurant $restaurant, Item $item): RedirectResponse
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant et que l'item appartient bien à ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }
        
        // Retirer l'item du menu
        $item->is_in_menu = false;
        $item->save();
        
        return redirect()->back()->with('success', 'Item retiré du menu avec succès.');
    }
}
