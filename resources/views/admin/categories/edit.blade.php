@extends('layout.adminlte')

@section('title', 'Modifier une catégorie')

@section('content_header')
    <h1>Modifier la catégorie : {{ $category->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la catégorie</h3>
                </div>
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name">Nom de la catégorie</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>Items dans cette catégorie</h4>
                            </div>
                            <div class="card-body">
                                @if($category->items->isEmpty())
                                    <p class="text-muted">Cette catégorie ne contient aucun item pour le moment.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Description</th>
                                                    <th>Restaurants</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($category->items as $item)
                                                    <tr>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ Str::limit($item->description, 50) }}</td>
                                                        <td>{{ $item->restaurants_count ?? $item->restaurants->count() }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i> Modifier
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
