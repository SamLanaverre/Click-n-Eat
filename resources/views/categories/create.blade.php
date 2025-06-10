@extends('layout.adminlte')
@section('main')
    <h1>Creation category</h1>
    
    <a href="{{ route('categories.index') }}">Retour à la liste</a>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <label for="name">Nom : </label>
        <input type="text" id="name" name="name" placeholder="Nom">
        <button type="submit">Envoyer</button>
    </form>
    
</body>
</html>