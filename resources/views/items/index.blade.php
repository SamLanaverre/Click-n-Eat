@extends('layout.main')

@section('styles')

@endsection

@section('main')
    <h1>Modifier un Item</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('items.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nom :</label>
        <input type="text" name="name" value="{{ $item->name }}" required>

        <label>Coût (centimes) :</label>
        <input type="number" name="cost" value="{{ $item->cost }}">

        <label>Prix (centimes) :</label>
        <input type="number" name="price" value="{{ $item->price }}" required>

        <label>Catégorie :</label>
        <select name="category_id" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label>Disponible :</label>
        <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>

        <button type="submit">Modifier</button>
    </form>

    <a href="{{ route('items.index') }}">Retour</a>
@endsection

@section('scripts')

@endsection
