@extends('layout.adminlte')

@section('header', 'Gestion des items')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des items</h3>
        <div class="card-tools">
            @if(auth()->user() && auth()->user()->isRestaurateur())
            <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Créer un item
            </a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if($items->isEmpty())
            <div class="alert alert-info m-3">Aucun item disponible.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Coût</th>
                            <th>Prix</th>
                            <th>Catégorie</th>
                            <th>Disponible</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->cost }} €</td>
                                <td>{{ $item->price }} €</td>
                                <td>{{ $item->category->name }}</td>
                                <td>
                                    @if($item->is_active)
                                        <span class="badge badge-success">Oui</span>
                                    @else
                                        <span class="badge badge-danger">Non</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user() && auth()->user()->isRestaurateur())
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-xs btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet item?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
