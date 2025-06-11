@extends('layout.app')

@section('title', 'Restaurants proposant ' . $category->name)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Restaurants proposant des plats de la catégorie "{{ $category->name }}"</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Toutes les catégories
            </a>
        </div>
    </div>

    @if($restaurants->isEmpty())
        <div class="alert alert-info">
            Aucun restaurant ne propose actuellement des plats dans cette catégorie.
        </div>
    @else
        <div class="row">
            @foreach($restaurants as $restaurant)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($restaurant->image_path)
                            <img src="{{ asset('storage/' . $restaurant->image_path) }}" class="card-img-top" alt="{{ $restaurant->name }}">
                        @else
                            <img src="{{ asset('img/default-restaurant.jpg') }}" class="card-img-top" alt="{{ $restaurant->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $restaurant->name }}</h5>
                            <p class="card-text">{{ Str::limit($restaurant->description, 100) }}</p>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $restaurant->address }}
                                </small>
                            </p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-primary">
                                Voir le restaurant
                            </a>
                            <a href="{{ route('restaurants.menu', $restaurant) }}" class="btn btn-outline-secondary">
                                Voir le menu
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
