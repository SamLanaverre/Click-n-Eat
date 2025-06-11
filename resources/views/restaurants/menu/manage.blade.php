@extends('layout.adminlte')

@section('title', 'Gestion du menu - ' . $restaurant->name)

@section('content_header')
    <h1>Gestion du menu - {{ $restaurant->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Menu actuel -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Menu actuel</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" id="menu-search" class="form-control" placeholder="Rechercher un item...">
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Catégories</th>
                                <th>Prix</th>
                                <th>Actif</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menuItems as $item)
                                <tr class="menu-item">
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @foreach ($item->categories as $category)
                                            <span class="badge badge-info">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($item->pivot->price, 2) }} €</td>
                                    <td>
                                        @if ($item->pivot->is_active)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editItemModal{{ $item->id }}">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                        <form action="{{ route('restaurants.menu.removeItem', [$restaurant, $item]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer cet item du menu ?')">
                                                <i class="fas fa-trash"></i> Retirer
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal pour éditer l'item -->
                                <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('restaurants.menu.updateItem', [$restaurant, $item]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Modifier {{ $item->name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="price{{ $item->id }}">Prix</label>
                                                        <input type="number" step="0.01" min="0" class="form-control" id="price{{ $item->id }}" name="price" value="{{ $item->pivot->price }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="is_active{{ $item->id }}" name="is_active" {{ $item->pivot->is_active ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_active{{ $item->id }}">Actif</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Catégories</label>
                                                        <div>
                                                            @foreach ($item->categories as $category)
                                                                <span class="badge badge-info">{{ $category->name }}</span>
                                                            @endforeach
                                                        </div>
                                                        <small class="form-text text-muted">Pour modifier les catégories, utilisez la gestion des items globaux.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun item dans le menu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ajouter un item au menu -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ajouter un item au menu</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('restaurants.menu.addItem', $restaurant) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="item_id">Item</label>
                            <select class="form-control select2" id="item_id" name="item_id" required>
                                <option value="">Sélectionnez un item</option>
                                @foreach ($availableItems as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} 
                                        @if ($item->categories->count() > 0)
                                            ({{ $item->categories->pluck('name')->join(', ') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Prix</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                                <label class="custom-control-label" for="is_active">Actif</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter au menu</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Filtrer par catégorie</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <select class="form-control" id="category-filter">
                            <option value="all">Toutes les catégories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Gestion globale</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.items.index') }}" class="btn btn-info btn-block">
                        <i class="fas fa-list"></i> Gérer les items globaux
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-block mt-2">
                        <i class="fas fa-plus"></i> Créer une nouvelle catégorie
                    </a>
                    <a href="{{ route('admin.items.create') }}" class="btn btn-primary btn-block mt-2">
                        <i class="fas fa-plus"></i> Créer un nouvel item
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Initialiser Select2
            $('.select2').select2();

            // Recherche dans le menu
            $("#menu-search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".menu-item").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Filtre par catégorie
            $("#category-filter").on("change", function() {
                var categoryId = $(this).val();
                
                if (categoryId === 'all') {
                    $(".menu-item").show();
                } else {
                    $(".menu-item").each(function() {
                        var categoryBadges = $(this).find("td:nth-child(2)").text();
                        var showItem = categoryBadges.includes($("#category-filter option:selected").text());
                        $(this).toggle(showItem);
                    });
                }
            });
        });
    </script>
@stop
