@extends('layout.adminlte')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des items</h1>
        <div>
            <a href="{{ route('restaurants.menu.index', $restaurant) }}" class="btn btn-info mr-2">
                <i class="fas fa-utensils"></i> Voir le menu
            </a>
            <a href="{{ route('restaurants.items.create', $restaurant) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un item
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($items->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i> Vous n'avez pas encore créé d'items. Commencez par en ajouter un !
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Prix</th>
                                <th>Catégories</th>
                                <th>Statut</th>
                                <th>Menu</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-thumbnail" style="max-height: 50px;">
                                        @else
                                            <span class="text-muted"><i class="fas fa-image"></i> Pas d'image</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                                    <td>
                                        @foreach($item->categories as $category)
                                            <span class="badge badge-info">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_in_menu)
                                            <form action="{{ route('restaurants.menu.remove', [$restaurant, $item]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Retirer du menu">
                                                    <i class="fas fa-minus-circle"></i> Retirer du menu
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('restaurants.menu.add', [$restaurant, $item]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Ajouter au menu">
                                                    <i class="fas fa-plus-circle"></i> Ajouter au menu
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('restaurants.items.edit', [$restaurant, $item]) }}" class="btn btn-sm btn-primary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('restaurants.items.destroy', [$restaurant, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
