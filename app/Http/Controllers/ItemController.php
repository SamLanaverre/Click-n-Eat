<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;

class ItemController extends Controller {
    public function index() {
        $items = Item::with('category')->get();
        return view('items.index', compact('items'));
    }
    
    public function show($id) { 
        $item = Item::with('category')->findOrFail($id);
        return view('items.show', compact('item'));
    }

    #[Authenticate]
    public function create() {
        $this->authorize('create', Item::class);
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    #[Authenticate]
    public function store(Request $request) {
        $this->authorize('create', Item::class);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'nullable|integer',
            'price' => 'required|integer',
            'is_active' => 'boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Item ajouté avec succès.');
    }

    #[Authenticate]
    public function edit($id) {
        $item = Item::findOrFail($id);
        $this->authorize('update', $item);
        
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    #[Authenticate]
    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);
        $this->authorize('update', $item);

        $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'nullable|integer',
            'price' => 'required|integer',
            'is_active' => 'boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Item mis à jour.');
    }

    #[Authenticate]
    public function destroy($id) {
        $item = Item::findOrFail($id);
        $this->authorize('delete', $item);
        
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item supprimé.');
    }
}
