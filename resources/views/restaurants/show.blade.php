@extends('layout.adminlte')

@section('title', isset($restaurant) ? $restaurant->name : 'Détail du restaurant')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ isset($restaurant) ? $restaurant->name : 'Détail du restaurant' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">Restaurants</a></li>
                    <li class="breadcrumb-item active">{{ isset($restaurant) ? $restaurant->name : 'Détail' }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <a href="{{ route('restaurants.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">
                {{ $restaurant->name }}
                @if($restaurant->is_active)
                    <span class="badge badge-success">Actif</span>
                @else
                    <span class="badge badge-danger">Inactif</span>
                @endif
            </h1>
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
                <div class="col-md-3 text-center mb-3">
                    @if($restaurant->logo)
                        <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                    @else
                        <div class="bg-light rounded p-4 mb-3">
                            <i class="fas fa-store fa-5x text-secondary"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-5">
                    <h5 class="border-bottom pb-2">Informations</h5>
                    <p class="mb-1"><i class="fas fa-info-circle mr-2"></i> <strong>Description :</strong> {{ $restaurant->description }}</p>
                    <p class="mb-1"><i class="fas fa-map-marker-alt mr-2"></i> <strong>Adresse :</strong> {{ $restaurant->address }}</p>
                    <p class="mb-1"><i class="fas fa-phone mr-2"></i> <strong>Téléphone :</strong> {{ $restaurant->phone }}</p>
                    @if($restaurant->email)
                        <p class="mb-1"><i class="fas fa-envelope mr-2"></i> <strong>Email :</strong> {{ $restaurant->email }}</p>
                    @endif
                    <p class="mb-1"><i class="fas fa-user mr-2"></i> <strong>Propriétaire :</strong> {{ $restaurant->owner->name }}</p>
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
                    
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <i class="fas fa-clock mr-2"></i> <strong>Horaires d'ouverture</strong>
                        </div>
                        <ul class="list-group list-group-flush">
                            @forelse($restaurant->opening_hours ?? [] as $day => $hours)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ ucfirst($day) }}</span>
                                    <span>
                                        @if(is_array($hours))
                                            {{ implode(', ', $hours) }}
                                        @else
                                            {{ $hours }}
                                        @endif
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Horaires non spécifiés</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            
            <hr>
            <h4 class="mt-4 mb-3"><i class="fas fa-utensils mr-2"></i> Aperçu du menu</h4>
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
                    <div class="col-12 alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Aucune catégorie/menu disponible.
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4 text-center">
                @auth
                    @if(auth()->user()->role === 'client')
                        <a href="{{ route('restaurants.menu', $restaurant) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-shopping-basket"></i> Voir le menu complet / Commander
                        </a>
                    @elseif(auth()->user()->role === 'admin' || (auth()->user()->role === 'restaurateur' && auth()->user()->id === $restaurant->owner_id))
                        <a href="{{ route('categories.create', ['restaurant' => $restaurant->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter une catégorie
                        </a>
                    @endif
                @else
                    <a href="{{ route('restaurants.menu', $restaurant) }}" class="btn btn-info btn-lg">
                        <i class="fas fa-utensils"></i> Voir le menu complet
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
