@extends('layout.adminlte')

@section('title', $category->name . ' - Édition')

@section('styles')
<style>
    /* Styles pour les cartes d'items */
    .item-card {
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .item-card .card-body {
        flex: 1 1 auto;
    }
    
    /* Styles pour la recherche */
    .search-box {
        position: relative;
        margin-bottom: 20px;
    }
    .search-box .fa-search {
        position: absolute;
        top: 12px;
        left: 12px;
        color: #aaa;
        z-index: 2;
    }
    .search-input {
        padding-left: 35px;
    }
    
    /* Styles pour les filtres et conteneurs */
    .filter-container {
        margin-bottom: 20px;
        align-items: center;
    }
    
    /* Styles pour les badges */
    .badge-restaurant-count {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.9rem;
        z-index: 1;
    }
    
    /* Styles pour les images */
    .card-img-container {
        height: 180px;
        overflow: hidden;
        position: relative;
    }
    .card-img-top {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
    
    /* Styles pour la vue en liste */
    .list-view .item-card {
        flex-direction: row;
    }
    .list-view .card-img-container {
        width: 150px;
        height: 100%;
        min-height: 120px;
    }
    .list-view .card-body {
        padding: 1rem;
    }
    
    /* Améliorations pour le formulaire */
    .form-section {
        padding: 1.25rem;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
        background-color: #f8f9fa;
        margin-bottom: 1rem;
    }
    
    /* Ajustements pour les boutons */
    .btn-group .btn {
        margin-right: 2px;
    }
</style>
@endsection

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $category->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Catégories</a></li>
                    <li class="breadcrumb-item active">Édition</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Informations de la catégorie et formulaire d'édition -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Détails et modification</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Colonne des détails -->
                <div class="col-md-6">
                    <h4>Informations</h4>
                    <dl class="row">
                        <dt class="col-sm-4">Identifiant</dt>
                        <dd class="col-sm-8">{{ $category->id }}</dd>

                        <dt class="col-sm-4">Créée le</dt>
                        <dd class="col-sm-8">{{ $category->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Dernière modification</dt>
                        <dd class="col-sm-8">{{ $category->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
                
                <!-- Colonne du formulaire d'édition -->
                <div class="col-md-6">
                    <h4>Modifier</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                        @csrf 
                        @method('put')
                        <div class="form-group">
                            <label for="name">Nom de la catégorie</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nom" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Items dans cette catégorie -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Items dans cette catégorie</h3>
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
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="fas fa-utensils fa-3x text-secondary"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                                    <p class="card-text"><small class="text-muted">Ajouté le {{ $item->created_at->format('d/m/Y') }}</small></p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Éditer
                                        </a>
                                        @if(auth()->user() && auth()->user()->isAdmin())
                                            <form action="{{ route('categories.removeItem', [$category, $item]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment retirer cet item de la catégorie?')">
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
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Recherche d'items
        const searchInput = document.getElementById('itemSearch');
        const itemsContainer = document.getElementById('itemsContainer');
        const itemCards = document.querySelectorAll('.item-card-container');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            
            itemCards.forEach(function(card) {
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
        
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const itemsArray = Array.from(itemCards);
            
            itemsArray.sort(function(a, b) {
                if (sortValue === 'name') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortValue === 'restaurants') {
                    return b.getAttribute('data-restaurants') - a.getAttribute('data-restaurants');
                } else if (sortValue === 'newest') {
                    return b.getAttribute('data-date') - a.getAttribute('data-date');
                }
            });
            
            itemsArray.forEach(function(item) {
                itemsContainer.appendChild(item);
            });
        });
        
        // Changement de vue (grille/liste)
        const gridViewBtn = document.getElementById('gridView');
        const listViewBtn = document.getElementById('listView');
        
        gridViewBtn.addEventListener('click', function() {
            itemCards.forEach(function(card) {
                card.classList.remove('col-12');
                card.classList.add('col-md-4');
            });
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
        });
        
        listViewBtn.addEventListener('click', function() {
            itemCards.forEach(function(card) {
                card.classList.remove('col-md-4');
                card.classList.add('col-12');
            });
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
        });
    });
</script>
@endsection