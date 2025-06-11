@extends('layout.adminlte')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Modifier l'utilisateur</h3>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group row mb-3">
                    <label for="name" class="col-sm-2 col-form-label">Nom</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="role" class="col-sm-2 col-form-label">Rôle</label>
                    <div class="col-sm-10">
                        @if($user->role === 'admin' && auth()->id() !== $user->id)
                            {{-- Si c'est un admin et ce n'est pas l'utilisateur actuel, on affiche un champ désactivé mais on ajoute un champ caché --}}
                            <select class="form-control" id="role" disabled>
                                <option value="admin" selected>Administrateur</option>
                            </select>
                            <input type="hidden" name="role" value="admin">
                            <small class="form-text text-muted">Vous ne pouvez pas modifier le rôle d'un autre administrateur.</small>
                        @else
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                                <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Client</option>
                                <option value="restaurateur" {{ old('role', $user->role) === 'restaurateur' ? 'selected' : '' }}>Restaurateur</option>
                                @if(auth()->user()->role === 'admin')
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                @endif
                            </select>
                        @endif
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="password" class="col-sm-2 col-form-label">Nouveau mot de passe</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Laissez vide pour conserver le mot de passe actuel">
                        <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row mb-3">
                    <label for="password_confirmation" class="col-sm-2 col-form-label">Confirmer le mot de passe</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
