@extends('layout.adminlte')

@section('main')
<div class="container py-4">
    <a href="{{ route('restaurants.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">{{ $restaurant->name }}</h1>
            @auth
                @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'restaurateur' && auth()->user()->id === $restaurant->owner_id))
                    <div>
                        <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="{{ route('categories.index', ['restaurant' => $restaurant->id]) }}" class="btn btn-sm btn-info"><i class="fas fa-utensils"></i> Gérer le menu</a>
                        <a href="{{ route('restaurants.orders.index', $restaurant) }}" class="btn btn-sm btn-primary"><i class="fas fa-receipt"></i> Voir les commandes</a>
                    </div>
                @endif
            @endauth
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p class="mb-1"><strong>Description :</strong> {{ $restaurant->description }}</p>
                    <p class="mb-1"><strong>Adresse :</strong> {{ $restaurant->address }}</p>
                    <p class="mb-1"><strong>Téléphone :</strong> {{ $restaurant->phone }}</p>
                    <p class="mb-1"><strong>Horaires d'ouverture :</strong></p>
                    <ul>
                        @foreach($restaurant->opening_hours ?? [] as $day => $hours)
                            <li>{{ ucfirst($day) }} : {{ $hours }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-4">
                    @auth
                        @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'restaurateur' && auth()->user()->id === $restaurant->owner_id))
                            <div class="alert alert-info text-center">
                                <strong>Commandes aujourd'hui :</strong><br>
                                <span class="display-4">{{ $ordersToday }}</span>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            <hr>
            <h4 class="mt-3">Aperçu du menu</h4>
            <div class="row">
                @forelse($restaurant->categories as $category)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <strong>{{ $category->name }}</strong>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach($category->items->take(3) as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $item->name }}
                                        <span class="badge bg-primary">{{ number_format($item->price / 100, 2, ',', ' ') }} €</span>
                                    </li>
                                @endforeach
                                @if($category->items->count() > 3)
                                    <li class="list-group-item text-center text-muted">…</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted">Aucune catégorie/menu disponible.</div>
                @endforelse
            </div>
            @auth
                @if(auth()->user()->role === 'client')
                    <div class="mt-4 text-center">
                        <a href="{{ route('restaurants.menu', $restaurant) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-shopping-basket"></i> Voir le menu complet / Commander
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection