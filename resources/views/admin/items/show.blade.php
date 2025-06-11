@extends('layout.adminlte')

@section('title', $item->name . ' - Détails')

@section('content_header')
    <h1>{{ $item->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="btn-group">
                <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
                <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?')">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Image</h3>
                </div>
                <div class="card-body text-center">
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="img-fluid">
                    @else
                        <div class="alert alert-info">
                            Aucune image disponible pour cet item
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">ID</dt>
                        <dd class="col-sm-9">{{ $item->id }}</dd>

                        <dt class="col-sm-3">Nom</dt>
                        <dd class="col-sm-9">{{ $item->name }}</dd>

                        <dt class="col-sm-3">Description</dt>
                        <dd class="col-sm-9">{{ $item->description ?: 'Aucune description' }}</dd>

                        <dt class="col-sm-3">Catégories</dt>
                        <dd class="col-sm-9">
                            @forelse($item->categories as $category)
                                <a href="{{ route('categories.show', $category) }}" class="badge badge-info">
                                    {{ $category->name }}
                                </a>
                            @empty
                                <span class="text-muted">Aucune catégorie associée</span>
                            @endforelse
                        </dd>

                        <dt class="col-sm-3">Créé le</dt>
                        <dd class="col-sm-9">{{ $item->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-3">Dernière modification</dt>
                        <dd class="col-sm-9">{{ $item->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Restaurants proposant cet item</h3>
                </div>
                <div class="card-body">
                    @if($item->restaurants->isEmpty())
                        <div class="alert alert-info">
                            Cet item n'est proposé par aucun restaurant pour le moment.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Restaurant</th>
                                        <th>Prix</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->restaurants as $restaurant)
                                        <tr>
                                            <td>
                                                <a href="{{ route('restaurants.show', $restaurant) }}">
                                                    {{ $restaurant->name }}
                                                </a>
                                            </td>
                                            <td>{{ number_format($restaurant->pivot->price, 2) }} €</td>
                                            <td>
                                                @if($restaurant->pivot->is_active)
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-danger">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('restaurants.menu.manage', $restaurant) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-utensils"></i> Gérer le menu
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
