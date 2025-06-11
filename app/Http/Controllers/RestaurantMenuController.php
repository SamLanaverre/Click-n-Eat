<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Middleware\Authenticate;

class RestaurantMenuController extends Controller
{
    /**
     * Affiche l'interface de gestion du menu d'un restaurant
     */
    #[Authenticate]
    public function manage(Restaurant $restaurant): View
    {
        // Vérifier que l'utilisateur a le droit de gérer ce restaurant
        Gate::authorize('update', $restaurant);
        
        // Récupérer les items du restaurant avec leurs catégories
        $menuItems = $restaurant->items()
            ->with('categories')
            ->withPivot('price', 'is_active')
            ->orderBy('name')
            ->get();
        
        // Récupérer tous les items disponibles pour ajout au menu
        $availableItems = Item::whereDoesntHave('restaurants', function($query) use ($restaurant) {
            $query->where('restaurants.id', $restaurant->id);
        })
        ->orderBy('name')
        ->get();
        
        // Récupérer toutes les catégories pour le filtrage
        $categories = Category::orderBy('name')->get();
        
        return view('restaurants.menu.manage', compact('restaurant', 'menuItems', 'availableItems', 'categories'));
    }
    
    /**
     * Ajoute un item au menu du restaurant
     */
    #[Authenticate]
    public function addItem(Request $request, Restaurant $restaurant): RedirectResponse
    {
        // Vérifier que l'utilisateur a le droit de gérer ce restaurant
        Gate::authorize('update', $restaurant);
        
        // Valider les données
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        
        // Vérifier que l'item n'est pas déjà dans le menu
        $itemExists = $restaurant->items()->where('items.id', $validated['item_id'])->exists();
        
        if ($itemExists) {
            return redirect()->route('restaurants.menu.manage', $restaurant)
                ->with('error', 'Cet item est déjà dans le menu du restaurant.');
        }
        
        // Ajouter l'item au menu
        $restaurant->items()->attach($validated['item_id'], [
            'price' => $validated['price'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        return redirect()->route('restaurants.menu.manage', $restaurant)
            ->with('status', 'L\'item a été ajouté au menu avec succès.');
    }
    
    /**
     * Met à jour un item du menu du restaurant
     */
    #[Authenticate]
    public function updateItem(Request $request, Restaurant $restaurant, Item $item): RedirectResponse
    {
        // Vérifier que l'utilisateur a le droit de gérer ce restaurant
        Gate::authorize('update', $restaurant);
        
        // Valider les données
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        
        // Vérifier que l'item est dans le menu
        $menuItem = $restaurant->items()->where('items.id', $item->id)->first();
        
        if (!$menuItem) {
            return redirect()->route('restaurants.menu.manage', $restaurant)
                ->with('error', 'Cet item n\'est pas dans le menu du restaurant.');
        }
        
        // Mettre à jour l'item
        $restaurant->items()->updateExistingPivot($item->id, [
            'price' => $validated['price'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        return redirect()->route('restaurants.menu.manage', $restaurant)
            ->with('status', 'L\'item a été mis à jour avec succès.');
    }
    
    /**
     * Retire un item du menu du restaurant
     */
    #[Authenticate]
    public function removeItem(Restaurant $restaurant, Item $item): RedirectResponse
    {
        // Vérifier que l'utilisateur a le droit de gérer ce restaurant
        Gate::authorize('update', $restaurant);
        
        // Vérifier que l'item est dans le menu
        $menuItem = $restaurant->items()->where('items.id', $item->id)->first();
        
        if (!$menuItem) {
            return redirect()->route('restaurants.menu.manage', $restaurant)
                ->with('error', 'Cet item n\'est pas dans le menu du restaurant.');
        }
        
        // Retirer l'item du menu
        $restaurant->items()->detach($item->id);
        
        return redirect()->route('restaurants.menu.manage', $restaurant)
            ->with('status', 'L\'item a été retiré du menu avec succès.');
    }
}
