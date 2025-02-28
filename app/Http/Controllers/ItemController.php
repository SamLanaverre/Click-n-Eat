<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller {
    public function index() {
        $items = Item::with('category')->get();
        return view('items.index', compact('items'));
    }
    
    public function show($id) { 
        $item = Item::with('category')->findOrFail($id);
        return view('items.show', compact('item'));
    }

    public function create() {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request) {
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

    public function edit($id) {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);

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

    public function destroy($id) {
        $item = Item::findOrFail($id);
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item supprimé.');
    }
}
