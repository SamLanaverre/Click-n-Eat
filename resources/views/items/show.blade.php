@extends('layouts.app')

@section('content')
<h1>Détail de l'Item</h1>

<p><strong>ID :</strong> {{ $item->id }}</p>
<p><strong>Nom :</strong> {{ $item->name }}</p>
<p><strong>Coût :</strong> {{ $item->cost ?? 'N/A' }} centimes</p>
<p><strong>Prix :</strong> {{ $item->price }} centimes</p>
<p><strong>Catégorie :</strong> {{ $item->category->name }}</p>
<p><strong>Disponible :</strong> {{ $item->is_active ? 'Oui' : 'Non' }}</p>
<p><strong>Créé le :</strong> {{ $item->created_at }}</p>
<p><strong>Mis à jour le :</strong> {{ $item->updated_at }}</p>

<a href="{{ route('items.index') }}">Retour</a>
@endsection