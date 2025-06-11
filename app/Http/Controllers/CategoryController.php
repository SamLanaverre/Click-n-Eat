<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Item;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Affiche la liste des catégories globales
     */
    public function index(): View {
        $categories = Category::withCount('items')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création d'une catégorie
     */
    #[Authenticate]
    public function create(): View {
        Gate::authorize('create', Category::class);
        return view('categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     */
    #[Authenticate]
    public function store(Request $request): RedirectResponse {
        Gate::authorize('create', Category::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        
        Category::create([
            'name' => $validated['name']
        ]);

        return redirect()->route('categories.index')->with('status', 'Catégorie créée avec succès');
    }

    /**
     * Affiche les détails d'une catégorie
     */
    public function show(Category $category): View {
        // Charger les items associés à cette catégorie
        $category->load('items');
        
        // Trouver les restaurants qui proposent des items de cette catégorie
        $restaurants = Restaurant::whereHas('items', function($query) use ($category) {
            $query->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            });
        })->get();
        
        return view('categories.show', compact('category', 'restaurants'));
    }

    /**
     * Affiche le formulaire d'édition d'une catégorie
     */
    #[Authenticate]
    public function edit(Category $category): View {
        Gate::authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     */
    #[Authenticate]
    public function update(Request $request, Category $category): RedirectResponse {
        Gate::authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);
        
        $category->update([
            'name' => $validated['name']
        ]);

        return redirect()->route('categories.index')->with('status', 'Catégorie mise à jour avec succès');
    }

    /**
     * Supprime une catégorie
     */
    #[Authenticate]
    public function destroy(Category $category): RedirectResponse {
        Gate::authorize('delete', $category);
        
        // Vérifier si la catégorie est utilisée par des items
        if ($category->items()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle est associée à un ou plusieurs items.');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')->with('status', 'Catégorie supprimée avec succès');
    }
    
    /**
     * Affiche les restaurants qui proposent des items d'une catégorie spécifique
     */
    public function restaurants(Category $category): View {
        $restaurants = Restaurant::whereHas('items', function($query) use ($category) {
            $query->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            });
        })->get();
        
        return view('categories.restaurants', compact('category', 'restaurants'));
    }
    
    /**
     * Ajoute un item à une catégorie
     */
    public function addItem(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);
        
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);
        
        // Vérifier que l'item n'est pas déjà dans cette catégorie
        if (!$category->items()->where('items.id', $validated['item_id'])->exists()) {
            $category->items()->attach($validated['item_id']);
            return back()->with('status', 'Item ajouté à la catégorie avec succès');
        }
        
        return back()->with('error', 'Cet item est déjà dans cette catégorie');
    }

    /**
     * Retire un item d'une catégorie
     */
    public function removeItem(Category $category, Item $item): RedirectResponse
    {
        Gate::authorize('update', $category);
        
        $category->items()->detach($item->id);
        return back()->with('status', 'Item retiré de la catégorie avec succès');
    }
}
