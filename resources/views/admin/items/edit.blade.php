@extends('layout.adminlte')

@section('title', 'Modifier un item')

@section('content_header')
    <h1>Modifier l'item : {{ $item->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de l'item</h3>
                </div>
                <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
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
                            <label for="name">Nom de l'item</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $item->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $item->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            @if($item->image_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="max-height: 100px;">
                                    <p class="text-muted">Image actuelle</p>
                                </div>
                            @endif
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                                    <label class="custom-file-label" for="image">Choisir une nouvelle image</label>
                                </div>
                            </div>
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Laissez vide pour conserver l'image actuelle.</small>
                        </div>

                        <div class="form-group">
                            <label for="categories">Catégories</label>
                            <select class="form-control select2 @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $item->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Sélectionnez une ou plusieurs catégories pour cet item.</small>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>Restaurants proposant cet item</h4>
                            </div>
                            <div class="card-body">
                                @if($item->restaurants->isEmpty())
                                    <p class="text-muted">Cet item n'est proposé par aucun restaurant pour le moment.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Restaurant</th>
                                                    <th>Prix</th>
                                                    <th>Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->restaurants as $restaurant)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('restaurants.show', $restaurant) }}">
                                                                {{ $restaurant->name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ number_format($restaurant->pivot->price, 2) }} €</td>
                                                        <td>
                                                            @if($restaurant->pivot->is_active)
                                                                <span class="badge badge-success">Actif</span>
                                                            @else
                                                                <span class="badge badge-danger">Inactif</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-muted mt-2">
                                        <small>Note: Pour modifier les prix ou la disponibilité, utilisez la gestion du menu du restaurant concerné.</small>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Initialiser Select2
            $('.select2').select2({
                placeholder: 'Sélectionnez des catégories'
            });

            // Afficher le nom du fichier sélectionné
            $('input[type="file"]').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });
    </script>
@stop
