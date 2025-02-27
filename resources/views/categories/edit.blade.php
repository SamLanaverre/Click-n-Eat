@extends('layout.main')

@section('main')
    <h1>Modification category</h1>

    <a href="{{ route('categories.index') }}">Retour à la liste</a>

    <form action="{{ route('categories.update', $category->id ) }}" method="POST">
        @csrf 
        @method('put')
        <label for="name">Nom : </label>
        <input type="text" id="name" name="name" placeholder="Nom" value="{{ $category->name }}">
        <button type="submit">Envoyer</button>
    </form>
@endsection