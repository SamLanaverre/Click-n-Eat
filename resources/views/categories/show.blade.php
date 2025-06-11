@extends('layout.app')

@section('title', $category->name . ' - Catégorie')

@section('styles')
<style>
    .item-card {
        transition: transform 0.3s;
    }
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .search-box {
        position: relative;
        margin-bottom: 20px;
    }
    .search-box .fa-search {
        position: absolute;
        top: 12px;
        left: 12px;
        color: #aaa;
    }
    .search-input {
        padding-left: 35px;
    }
    .filter-container {
        margin-bottom: 20px;
    }
    .badge-restaurant-count {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.9rem;
    }
</style>
@endsection

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
            <div>
                @if(auth()->user() && auth()->user()->isAdmin())
                <a href="{{ route('admin.items.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Créer un nouvel item
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <!-- Filtres et recherche -->
            <div class="row filter-container">
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="itemSearch" class="form-control search-input" placeholder="Rechercher un item...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="sortItems" class="form-control">
                        <option value="name">Trier par nom</option>
                        <option value="restaurants">Trier par nombre de restaurants</option>
                        <option value="newest">Plus récents d'abord</option>
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="gridView">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="listView">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            @if($category->items->isEmpty())
                <div class="alert alert-info">Aucun item dans cette catégorie pour le moment.</div>
            @else
                <div class="row" id="itemsContainer">
                    @foreach($category->items as $item)
                        <div class="col-md-4 mb-4 item-card-container" data-name="{{ strtolower($item->name) }}" data-restaurants="{{ $item->restaurants_count ?? 0 }}" data-date="{{ $item->created_at->timestamp }}">
                            <div class="card h-100 item-card position-relative">
                                <span class="badge badge-info badge-restaurant-count">{{ $item->restaurants_count ?? 0 }} restaurants</span>
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 180px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('img/default-food.jpg') }}" class="card-img-top" alt="{{ $item->name }}" style="height: 180px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                                    <p>
                                        <small class="text-muted">Ajouté le {{ $item->created_at->format('d/m/Y') }}</small>
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="btn-group w-100">
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Recherche d'items
        const searchInput = document.getElementById('itemSearch');
        const itemCards = document.querySelectorAll('.item-card-container');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            itemCards.forEach(card => {
                const itemName = card.getAttribute('data-name');
                if (itemName.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Tri des items
        const sortSelect = document.getElementById('sortItems');
        const itemsContainer = document.getElementById('itemsContainer');
        
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const itemsArray = Array.from(itemCards);
            
            itemsArray.sort((a, b) => {
                if (sortValue === 'name') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortValue === 'restaurants') {
                    return parseInt(b.getAttribute('data-restaurants')) - parseInt(a.getAttribute('data-restaurants'));
                } else if (sortValue === 'newest') {
                    return parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date'));
                }
            });
            
            itemsArray.forEach(item => {
                itemsContainer.appendChild(item);
            });
        });
        
        // Basculer entre vue grille et liste
        const gridViewBtn = document.getElementById('gridView');
        const listViewBtn = document.getElementById('listView');
        
        gridViewBtn.addEventListener('click', function() {
            itemCards.forEach(card => {
                card.classList.remove('col-md-12');
                card.classList.add('col-md-4');
            });
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
        });
        
        listViewBtn.addEventListener('click', function() {
            itemCards.forEach(card => {
                card.classList.remove('col-md-4');
                card.classList.add('col-md-12');
            });
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
        });
    });
</script>
@endsection
@endsection