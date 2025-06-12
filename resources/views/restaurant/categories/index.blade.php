@extends('layout.adminlte')

@section('header', 'Gestion des catégories - ' . $restaurant->name)

@section('content')
<div class="row mb-3">
    <div class="col-12 text-right">
        <a href="{{ route('restaurants.categories.create', $restaurant) }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Ajouter une catégorie
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Catégories du restaurant</h3>
    </div>
    <div class="card-body p-0">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Nombre d'items</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ Str::limit($category->description ?? '-', 50) }}</td>
                                <td>
                                    @php
                                        $itemCount = $restaurant->items->filter(function($item) use ($category) {
                                            return $item->categories->contains('id', $category->id);
                                        })->count();
                                    @endphp
                                    {{ $itemCount }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('restaurants.categories.edit', [$restaurant, $category]) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form action="{{ route('restaurants.categories.destroy', [$restaurant, $category]) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info m-3">
                <i class="fas fa-info-circle mr-2"></i> Aucune catégorie n'a été ajoutée à ce restaurant.
            </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('restaurant.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Retour au tableau de bord
    </a>
</div>
@endsection
