@extends('layout.adminlte')

@section('main')
<div class="container py-4">
    <a href="{{ route('restaurants.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    <div class="card">
        <div class="card-header"><h2 class="mb-0">Créer un restaurant</h2></div>
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
            <form action="{{ route('restaurants.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="2" required>{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Adresse <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="mb-3">
                    <label for="opening_hours" class="form-label">Horaires d'ouverture (JSON ou texte)</label>
                    <input type="text" class="form-control" id="opening_hours" name="opening_hours" placeholder='{"lundi":"9h-18h", ...}' value="{{ old('opening_hours') }}">
                    <small class="form-text text-muted">Exemple : {"lundi":"9h-18h","mardi":"9h-18h",...}</small>
                </div>
                <button type="submit" class="btn btn-success">Créer</button>
            </form>
        </div>
    </div>
</div>
@endsection