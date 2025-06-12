<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Middleware\Authenticate;

class ItemController extends Controller
{
    /**
     * Affiche la liste des items d'un restaurant
     */
    #[Authenticate]
    public function index(Restaurant $restaurant): View
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        $items = $restaurant->items()->with('categories')->get();
        
        return view('restaurant.items.index', [
            'restaurant' => $restaurant,
            'items' => $items
        ]);
    }

    /**
     * Affiche le formulaire de création d'un item
     */
    #[Authenticate]
    public function create(Restaurant $restaurant): View
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        // Récupérer toutes les catégories pour le formulaire
        $categories = Category::all();
        
        return view('restaurant.items.create', [
            'restaurant' => $restaurant,
            'categories' => $categories
        ]);
    }

    /**
     * Enregistre un nouvel item
     */
    #[Authenticate]
    public function store(Request $request, Restaurant $restaurant): RedirectResponse
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Créer l'item
        $item = new Item();
        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->price = $validated['price'];
        $item->is_active = $request->has('is_active');
        $item->restaurant_id = $restaurant->id;
        
        // Gestion de l'upload de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
            $item->image = $imagePath;
        }
        
        $item->save();
        
        // Associer les catégories
        if (isset($validated['categories'])) {
            $item->categories()->sync($validated['categories']);
        }
        
        return redirect()->route('restaurants.edit', $restaurant)
            ->with('success', 'Item ajouté avec succès au menu.');
    }

    /**
     * Affiche le formulaire d'édition d'un item
     */
    #[Authenticate]
    public function edit(Restaurant $restaurant, Item $item): View
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant et que l'item appartient bien à ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }
        
        // Récupérer toutes les catégories pour le formulaire
        $categories = Category::all();
        
        return view('restaurant.items.edit', [
            'restaurant' => $restaurant,
            'item' => $item,
            'categories' => $categories
        ]);
    }

    /**
     * Met à jour un item existant
     */
    #[Authenticate]
    public function update(Request $request, Restaurant $restaurant, Item $item): RedirectResponse
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant et que l'item appartient bien à ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Mettre à jour l'item
        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->price = $validated['price'];
        $item->is_active = $request->has('is_active');
        
        // Gestion de l'upload de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            
            $imagePath = $request->file('image')->store('items', 'public');
            $item->image = $imagePath;
        }
        
        $item->save();
        
        // Associer les catégories
        if (isset($validated['categories'])) {
            $item->categories()->sync($validated['categories']);
        }
        
        return redirect()->route('restaurants.edit', $restaurant)
            ->with('success', 'Item mis à jour avec succès.');
    }

    /**
     * Supprime un item
     */
    #[Authenticate]
    public function destroy(Restaurant $restaurant, Item $item): RedirectResponse
    {
        // Vérifier que l'utilisateur peut gérer ce restaurant et que l'item appartient bien à ce restaurant
        Gate::authorize('manageMenu', $restaurant);
        
        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }
        
        // Supprimer l'image si elle existe
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        
        // Supprimer l'item
        $item->delete();
        
        return redirect()->route('restaurants.edit', $restaurant)
            ->with('success', 'Item supprimé avec succès.');
    }
}
