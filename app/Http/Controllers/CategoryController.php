<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Auth\Middleware\Authenticate;

class CategoryController extends Controller
{
    public function index() {
        return view('categories.index', [
            'categories' => Category::all()
        ]);
    }

    #[Authenticate]
    public function create() {
        $this->authorize('create', Category::class);
        return view('categories.create');
    }


    #[Authenticate]
    public function store(Request $request) {
        $this->authorize('create', Category::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'restaurant_id' => 'required|exists:restaurants,id'
        ]);
        
        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès');
    }

    public function show($id) {
        return view('categories.show',
        ['category' => Category::findOrFail($id)]);
    }

    #[Authenticate]
    public function edit($id) {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);
        
        return view('categories.edit', ['category' => $category]);
    }

    #[Authenticate]
    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        
        $category->name = $validated['name'];
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès');
    }

    #[Authenticate]
    public function destroy(Request $request, $id) {
        $category = Category::findOrFail($id);
        $this->authorize('delete', $category);
        
        $category->delete();
        
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès');
    }
}
