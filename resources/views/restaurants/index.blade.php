@extends('layout.adminlte')

@section('main')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Restaurants</h1>
        @auth
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'restaurateur')
                <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un restaurant
                </a>
            @endif
        @endauth
    </div>
    <div class="row">
        @forelse($restaurants as $restaurant)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                        <p class="card-text text-muted mb-1"><i class="fas fa-map-marker-alt"></i> {{ $restaurant->address }}</p>
                        <p class="card-text">{{ Str::limit($restaurant->description, 80) }}</p>
                        <div class="mb-2">
                            <span class="badge bg-light text-dark">{{ $restaurant->categories->count() }} catégories</span>
                            <span class="badge bg-light text-dark">{{ $restaurant->categories->sum(fn($c) => $c->items->count()) }} plats</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i> Voir fiche
                            </a>
                            @auth
                                @if(auth()->user()->role === 'client')
                                    <a href="{{ route('restaurants.menu', $restaurant) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-shopping-basket"></i> Commander
                                    </a>
                                @endif
                                @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'restaurateur' && auth()->user()->id === $restaurant->owner_id))
                                    <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="{{ route('categories.index', ['restaurant' => $restaurant->id]) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-utensils"></i> Gérer menu
                                    </a>
                                    <form action="{{ route('restaurants.destroy', $restaurant) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Supprimer ce restaurant ?');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Aucun restaurant pour l’instant.</div>
        @endforelse
    </div>
    @endsection
</x-adminlte-layout>