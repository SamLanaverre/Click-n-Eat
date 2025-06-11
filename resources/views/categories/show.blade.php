@extends('layout.app')

@section('title', $category->name . ' - Catégorie')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ $category->name }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Toutes les catégories
            </a>
            <a href="{{ route('categories.restaurants', $category) }}" class="btn btn-success">
                <i class="fas fa-utensils"></i> Restaurants
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Détails de la catégorie</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Identifiant</dt>
                <dd class="col-sm-9">{{ $category->id }}</dd>

                <dt class="col-sm-3">Nom</dt>
                <dd class="col-sm-9">{{ $category->name }}</dd>

                <dt class="col-sm-3">Créée le</dt>
                <dd class="col-sm-9">{{ $category->created_at->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Dernière modification</dt>
                <dd class="col-sm-9">{{ $category->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Items dans cette catégorie</h3>
            @if(auth()->user() && auth()->user()->isAdmin())
            <a href="{{ route('admin.items.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Créer un nouvel item
            </a>
            @endif
        </div>
        <div class="card-body">
            @if($category->items->isEmpty())
                <div class="alert alert-info">Aucun item dans cette catégorie pour le moment.</div>
            @else
                <div class="row">
                    @foreach($category->items as $item)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="card-img-top" alt="{{ $item->name }}">
                                @else
                                    <img src="{{ asset('img/default-food.jpg') }}" class="card-img-top" alt="{{ $item->name }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                                    <p>
                                        <span class="badge badge-info">{{ $item->restaurants_count ?? 0 }} restaurants</span>
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                    @if(auth()->user() && auth()->user()->isAdmin())
                                    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i> Éditer
                                    </a>
                                    <form action="{{ route('categories.removeItem', ['category' => $category->id, 'item' => $item->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer cet item de cette catégorie ?')">
                                            <i class="fas fa-unlink"></i> Retirer
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if(auth()->user() && auth()->user()->isAdmin())
    <div class="card mt-4">
        <div class="card-header">
            <h3>Ajouter un item existant à cette catégorie</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.addItem', $category) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="item_id">Sélectionner un item</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">-- Choisir un item --</option>
                        @php
                            // Récupérer les IDs des items déjà dans la catégorie
                            $categoryItemIds = $category->items->pluck('id')->toArray();
                            // Récupérer tous les items qui ne sont pas dans cette catégorie
                            $availableItems = \App\Models\Item::whereNotIn('id', $categoryItemIds)->orderBy('name')->get();
                        @endphp
                        @foreach($availableItems as $availableItem)
                            <option value="{{ $availableItem->id }}">{{ $availableItem->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter à la catégorie
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection