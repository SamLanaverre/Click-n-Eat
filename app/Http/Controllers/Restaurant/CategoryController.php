<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories for a specific restaurant.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\View\View
     */
    public function index(Restaurant $restaurant): View
    {
        // Vérifier que l'utilisateur connecté est bien propriétaire du restaurant
        $this->authorize('view', $restaurant);
        
        // Récupérer les catégories du restaurant
        $categories = $restaurant->categories;
        
        return view('restaurant.categories.index', [
            'restaurant' => $restaurant,
            'categories' => $categories,
        ]);
    }
    
    /**
     * Show the form for creating a new category.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\View\View
     */
    public function create(Restaurant $restaurant): View
    {
        $this->authorize('update', $restaurant);
        
        return view('restaurant.categories.create', [
            'restaurant' => $restaurant,
        ]);
    }
    
    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $category = Category::create($validated);
        
        // Associer la catégorie aux items du restaurant si nécessaire
        // Cette logique dépend de votre modèle de données
        
        return redirect()->route('restaurants.categories.index', $restaurant)
            ->with('success', 'Catégorie créée avec succès.');
    }
    
    /**
     * Show the form for editing the specified category.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function edit(Restaurant $restaurant, Category $category): View
    {
        $this->authorize('update', $restaurant);
        
        return view('restaurant.categories.edit', [
            'restaurant' => $restaurant,
            'category' => $category,
        ]);
    }
    
    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Restaurant $restaurant, Category $category)
    {
        $this->authorize('update', $restaurant);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $category->update($validated);
        
        return redirect()->route('restaurants.categories.index', $restaurant)
            ->with('success', 'Catégorie mise à jour avec succès.');
    }
    
    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Restaurant $restaurant, Category $category)
    {
        $this->authorize('update', $restaurant);
        
        // Vérifier si la catégorie est utilisée par des items du restaurant
        // et gérer cette logique selon votre modèle de données
        
        $category->delete();
        
        return redirect()->route('restaurants.categories.index', $restaurant)
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
