<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RestaurantController extends Controller
{
    public function index(): View
    {
        $restaurants = auth()->user()->restaurants;
        return view('restaurants.index', compact('restaurants'));
    }

    public function create(): View
    {
        return view('restaurants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'opening_hours' => 'required|array'
        ]);

        $restaurant = auth()->user()->restaurants()->create($validated);

        return redirect()->route('restaurants.categories.index', $restaurant)
            ->with('success', 'Restaurant créé avec succès.');
    }

    public function edit(Restaurant $restaurant): View
    {
        $this->authorize('update', $restaurant);
        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('update', $restaurant);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'opening_hours' => 'required|array'
        ]);

        $restaurant->update($validated);

        return redirect()->route('restaurant.dashboard')
            ->with('success', 'Restaurant mis à jour avec succès.');
    }

    public function destroy(Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('delete', $restaurant);
        
        $restaurant->delete();

        return redirect()->route('restaurant.dashboard')
            ->with('success', 'Restaurant supprimé avec succès.');
    }

    public function show(Restaurant $restaurant): View
    {
        // Chargement des relations utiles pour la fiche détaillée
        $restaurant->load(['categories.items']);
        // Statistiques rapides (exemple)
        $ordersToday = $restaurant->orders()->whereDate('created_at', today())->count();
        return view('restaurants.show', [
            'restaurant' => $restaurant,
            'ordersToday' => $ordersToday,
        ]);
    }

    public function showMenu(Restaurant $restaurant): View
    {
        return view('restaurants.menu', [
            'restaurant' => $restaurant->load(['categories.items' => function($query) {
                $query->where('is_active', true);
            }])
        ]);
    }
}