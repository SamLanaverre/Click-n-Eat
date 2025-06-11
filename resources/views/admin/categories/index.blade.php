@extends('layout.adminlte')

@section('title', 'Gestion des catégories')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gestion des catégories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Catégories</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des catégories</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nouvelle catégorie
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nom</th>
                            <th>Nombre d'items</th>
                            <th>Date de création</th>
                            <th style="width: 200px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $category->items_count ?? $category->items->count() }}</span>
                                </td>
                                <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="confirmDelete('{{ route('admin.categories.destroy', $category) }}', 'la catégorie {{ $category->name }}')"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucune catégorie trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $categories->links() }}
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
