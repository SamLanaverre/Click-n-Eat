<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    /**
     * Constructeur avec middleware d'authentification
     */
    public function __construct()
    {
        // Applique le middleware auth sauf pour index et show qui sont publics
        $this->middleware('auth')->except(['index', 'show', 'showMenu']);
    }
    
    /**
     * Affiche la liste des restaurants
     */
    public function index(): View
    {
        // Affiche tous les restaurants pour tout le monde
        // Si l'utilisateur est connecté et restaurateur, affiche seulement ses restaurants
        if (Auth::check() && Auth::user()->role === 'restaurateur') {
            $restaurants = Auth::user()->restaurants;
        } else {
            $restaurants = Restaurant::all();
        }
        
        return view('restaurants.index', compact('restaurants'));
    }

    /**
     * Affiche le formulaire de création d'un restaurant
     */
    public function create(): View
    {
        $this->authorize('create', Restaurant::class);
        return view('restaurants.create');
    }

    /**
     * Enregistre un nouveau restaurant
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Restaurant::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'opening_hours' => 'nullable|string'
        ]);
        
        // Convertir les horaires d'ouverture en tableau si c'est un JSON valide
        if (isset($validated['opening_hours']) && is_string($validated['opening_hours'])) {
            try {
                $decoded = json_decode($validated['opening_hours'], true);
                if (is_array($decoded)) {
                    $validated['opening_hours'] = $decoded;
                }
            } catch (\Exception $e) {
                // Si ce n'est pas un JSON valide, on le garde comme une chaîne
            }
        }

        $restaurant = Auth::user()->restaurants()->create($validated);

        return redirect()->route('restaurants.show', $restaurant)
            ->with('success', 'Restaurant créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un restaurant
     */
    public function edit(Restaurant $restaurant): View
    {
        $this->authorize('update', $restaurant);
        return view('restaurants.edit', compact('restaurant'));
    }

    /**
     * Met à jour un restaurant existant
     */
    public function update(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('update', $restaurant);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'opening_hours' => 'nullable|string'
        ]);
        
        // Convertir les horaires d'ouverture en tableau si c'est un JSON valide
        if (isset($validated['opening_hours']) && is_string($validated['opening_hours'])) {
            try {
                $decoded = json_decode($validated['opening_hours'], true);
                if (is_array($decoded)) {
                    $validated['opening_hours'] = $decoded;
                }
            } catch (\Exception $e) {
                // Si ce n'est pas un JSON valide, on le garde comme une chaîne
            }
        }

        $restaurant->update($validated);

        return redirect()->route('restaurants.show', $restaurant)
            ->with('success', 'Restaurant mis à jour avec succès.');
    }

    /**
     * Supprime un restaurant
     */
    public function destroy(Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('delete', $restaurant);
        
        $restaurant->delete();

        return redirect()->route('restaurants.index')
            ->with('success', 'Restaurant supprimé avec succès.');
    }

    /**
     * Affiche la fiche détaillée d'un restaurant
     */
    public function show(Restaurant $restaurant): View
    {
        // Chargement des relations utiles pour la fiche détaillée
        $restaurant->load(['categories.items' => function($query) {
            $query->where('is_active', true)->take(3); // Limite à 3 items par catégorie pour l'aperçu
        }]);
        
        // Statistiques rapides (seulement pour admin/restaurateur)
        $ordersToday = 0;
        $canManage = false;
        
        if (Auth::check()) {
            $user = Auth::user();
            $canManage = $user->can('manageMenu', $restaurant);
            
            if ($canManage) {
                $ordersToday = $restaurant->orders()->whereDate('created_at', today())->count();
            }
        }
        
        return view('restaurants.show', [
            'restaurant' => $restaurant,
            'ordersToday' => $ordersToday,
            'canManage' => $canManage
        ]);
    }

    /**
     * Affiche le menu complet d'un restaurant
     */
    public function showMenu(Restaurant $restaurant): View
    {
        // Charge les catégories et items actifs pour le menu public
        $restaurant->load(['categories.items' => function($query) {
            $query->where('is_active', true);
        }]);
        
        return view('restaurants.menu', [
            'restaurant' => $restaurant
        ]);
    }
}