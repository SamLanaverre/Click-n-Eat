@extends('layout.adminlte')

@section('title', 'Gestion des items')

@section('content_header')
    <h1>Gestion des items globaux</h1>
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des items</h3>
            <div class="card-tools">
                <a href="{{ route('admin.items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un nouvel item
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Catégories</th>
                        <th>Restaurants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="max-height: 50px;">
                                @else
                                    <span class="text-muted">Aucune image</span>
                                @endif
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ Str::limit($item->description, 50) }}</td>
                            <td>
                                @foreach($item->categories as $category)
                                    <span class="badge badge-info">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $item->restaurants_count ?? $item->restaurants->count() }} restaurants</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.items.show', $item) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Aucun item disponible</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $items->links() }}
        </div>
    </div>
@stop
