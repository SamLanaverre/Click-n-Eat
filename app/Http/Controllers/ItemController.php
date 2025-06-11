<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ItemController extends Controller {
    /**
     * Affiche la liste des items globaux
     */
    public function index(): View {
        $items = Item::with('categories')->orderBy('name')->get();
        return view('items.index', compact('items'));
    }
    
    /**
     * Affiche les détails d'un item
     */
    public function show(Item $item): View { 
        $item->load('categories', 'restaurants');
        return view('items.show', compact('item'));
    }

    /**
     * Affiche le formulaire de création d'un item
     */
    #[Authenticate]
    public function create(): View {
        Gate::authorize('create', Item::class);
        $categories = Category::orderBy('name')->get();
        return view('items.create', compact('categories'));
    }

    /**
     * Enregistre un nouvel item
     */
    #[Authenticate]
    public function store(Request $request): RedirectResponse {
        Gate::authorize('create', Item::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Créer l'item
        $item = Item::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'cost' => $validated['cost'] ?? 0,
        ]);
        
        // Associer les catégories
        $item->categories()->attach($validated['categories']);

        return redirect()->route('items.index')->with('status', 'Item ajouté avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un item
     */
    #[Authenticate]
    public function edit(Item $item): View {
        Gate::authorize('update', $item);
        
        $categories = Category::orderBy('name')->get();
        $selectedCategories = $item->categories->pluck('id')->toArray();
        
        return view('items.edit', compact('item', 'categories', 'selectedCategories'));
    }

    /**
     * Met à jour un item
     */
    #[Authenticate]
    public function update(Request $request, Item $item): RedirectResponse {
        Gate::authorize('update', $item);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Mettre à jour l'item
        $item->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'cost' => $validated['cost'] ?? 0,
        ]);
        
        // Synchroniser les catégories
        $item->categories()->sync($validated['categories']);

        return redirect()->route('items.index')->with('status', 'Item mis à jour avec succès.');
    }

    /**
     * Supprime un item
     */
    #[Authenticate]
    public function destroy(Item $item): RedirectResponse {
        Gate::authorize('delete', $item);
        
        // Vérifier si l'item est utilisé dans des restaurants
        if ($item->restaurants()->exists()) {
            return redirect()->route('items.index')
                ->with('error', 'Impossible de supprimer cet item car il est utilisé dans un ou plusieurs restaurants.');
        }
        
        // Détacher toutes les catégories avant de supprimer
        $item->categories()->detach();
        $item->delete();
        
        return redirect()->route('items.index')->with('status', 'Item supprimé avec succès.');
    }
}
