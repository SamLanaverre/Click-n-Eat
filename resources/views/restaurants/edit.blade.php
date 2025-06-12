@extends('layout.adminlte')

@section('content')
<div class="container py-4">
    <a href="{{ route('restaurants.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    <div class="card">
        <div class="card-header"><h2 class="mb-0">Modifier le restaurant</h2></div>
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
            <form action="{{ route('restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $restaurant->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $restaurant->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $restaurant->address) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $restaurant->phone) }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $restaurant->email) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            @if($restaurant->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                                    <label class="custom-file-label" for="logo">Choisir un nouveau logo</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Formats acceptés : JPG, PNG, GIF. Max 2Mo.</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="opening_hours" class="form-label">Horaires d'ouverture (JSON ou texte)</label>
                            <textarea class="form-control" id="opening_hours" name="opening_hours" rows="3" placeholder='{"lundi":"9h-18h", ...}'>{{ old('opening_hours', is_array($restaurant->opening_hours) ? json_encode($restaurant->opening_hours, JSON_UNESCAPED_UNICODE) : $restaurant->opening_hours) }}</textarea>
                            <small class="form-text text-muted">Exemple : {"lundi":"9h-18h","mardi":"9h-18h",...}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $restaurant->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Restaurant actif
                                </label>
                                <small class="form-text text-muted d-block">Un restaurant inactif ne sera pas visible par les clients.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer les modifications</button>
                    <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Section de gestion des items du menu -->    
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Gestion du menu</h2>
            <a href="{{ route('restaurants.items.create', $restaurant) }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter un item
            </a>
        </div>
        <div class="card-body">
            @if($restaurant->items->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i> Vous n'avez pas encore d'items dans votre menu. Commencez par en ajouter un !
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Prix</th>
                                <th>Catégories</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($restaurant->items as $item)
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
                                        <div class="btn-group">
                                            <a href="{{ route('restaurants.items.edit', [$restaurant, $item]) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('restaurants.items.destroy', [$restaurant, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
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
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script pour afficher le nom du fichier sélectionné dans l'input file
    $(document).ready(function() {
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush
@endsection
