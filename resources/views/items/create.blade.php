@extends('layout.adminlte')

@section('content')
<h1>Ajouter un Item</h1>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('items.store') }}" method="POST">
    @csrf
    <label>Nom :</label>
    <input type="text" name="name" required>

    <label>Coût (centimes) :</label>
    <input type="number" name="cost">

    <label>Prix (centimes) :</label>
    <input type="number" name="price" required>

    <label>Catégorie :</label>
    <select name="category_id" required>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <label>Disponible :</label>
    <input type="checkbox" name="is_active" value="1" checked>

    <button type="submit">Ajouter</button>
</form>

<a href="{{ route('items.index') }}">Retour</a>
@endsection
