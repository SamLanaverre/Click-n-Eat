<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        return view('categories.index', [
            'categories' => Category::all()
        ]);
    }

    public function create() {
        return view('categories.create');
    }


    public function store(Request $request) {
        Category::create( $request->all() );

        return redirect()->route('categories.index');
    }

    public function show($id) {
        return view('categories.show',
        ['category' => Category::findOrFail($id)]);
    }

    public function edit($id) {
        return view('categories.edit',
        ['category' => Category::findOrFail($id)]);
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);

        $category->name = $request->get('name');
        $category->save();

        return redirect()->route('categories.index');
    }

    public function destroy(Request $request, $id) {
        if($request->get('id') == $id) {
            Category::destroy($id);
        }
        return redirect()->route('categories.index');
    }
}
