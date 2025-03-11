@extends('layouts.main')

@section('content')
    <h1>Modifier l'Item</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @isset($item)
        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="name">Nom :</label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
            </div>

            <div>
                <label for="cost">Coût (centimes) :</label>
                <input type="number" name="cost" value="{{ old('cost', $item->cost) }}" required>
            </div>

            <div>
                <label for="price">Prix (centimes) :</label>
                <input type="number" name="price" value="{{ old('price', $item->price) }}" required>
            </div>

            <div>
                <label for="category_id">Catégorie :</label>
                <select name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="is_active">Disponible :</label>
                <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>
            </div>

            <button type="submit">Modifier</button>
        </form>
    @else
        <p>Item non trouvé.</p>
    @endisset

    <a href="{{ route('items.index') }}">Retour à la liste des items</a>
@endsection
