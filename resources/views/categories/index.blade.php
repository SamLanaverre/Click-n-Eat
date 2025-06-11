@extends('layout.app')

@section('title', 'Catégories')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Catégories</h1>
            <p class="lead">Explorez nos catégories de plats et découvrez les restaurants qui les proposent.</p>
        </div>
        <div class="col-md-4 text-right">
            @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isRestaurateur()))
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Créer une catégorie
            </a>
            @endif
        </div>
    </div>

    <div class="row">
        @forelse($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">
                            <span class="badge badge-primary">{{ $category->items_count }} items</span>
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="btn-group w-100">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                            <a href="{{ route('categories.restaurants', $category) }}" class="btn btn-outline-success">
                                <i class="fas fa-utensils"></i> Restaurants
                            </a>
                            @if(auth()->user() && auth()->user()->isAdmin())
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-warning">
                                <i class="fas fa-edit"></i> Éditer
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Aucune catégorie disponible pour le moment.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
