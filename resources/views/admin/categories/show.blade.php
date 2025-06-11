@extends('layout.adminlte')

@section('title', 'Détails de la catégorie')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Détails de la catégorie</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Catégories</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Informations</h3>
                    </div>
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center">{{ $category->name }}</h3>
                        
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>ID</b> <a class="float-right">{{ $category->id }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Nombre d'items</b> <a class="float-right">{{ $category->items->count() }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Date de création</b> <a class="float-right">{{ $category->created_at->format('d/m/Y H:i') }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Dernière modification</b> <a class="float-right">{{ $category->updated_at->format('d/m/Y H:i') }}</a>
                            </li>
                        </ul>

                        <div class="btn-group w-100">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-danger" 
                                    onclick="confirmDelete('{{ route('admin.categories.destroy', $category) }}', 'la catégorie {{ $category->name }}')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Actions</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-block">
                            <i class="fas fa-eye"></i> Voir en public
                        </a>
                        <a href="{{ route('categories.restaurants', $category) }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-utensils"></i> Restaurants proposant cette catégorie
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Items dans cette catégorie</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th>Description</th>
                                        <th>Restaurants</th>
                                        <th style="width: 120px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($category->items as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                @if($item->image)
                                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-thumbnail" style="max-height: 50px;">
                                                @else
                                                    <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="img-thumbnail" style="max-height: 50px;">
                                                @endif
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ Str::limit($item->description, 50) }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $item->restaurants->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.items.show', $item) }}" class="btn btn-info btn-sm" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-warning btn-sm" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun item dans cette catégorie</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer <span id="deleteItemName"></span> ?</p>
                    <p class="text-danger">Cette action est irréversible et supprimera également toutes les associations avec les items.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function confirmDelete(url, itemName) {
        document.getElementById('deleteItemName').textContent = itemName;
        document.getElementById('deleteForm').action = url;
        $('#deleteConfirmationModal').modal('show');
    }
</script>
@endsection
