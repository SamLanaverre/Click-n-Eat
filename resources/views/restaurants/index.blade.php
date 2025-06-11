@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Gestion des restaurants</h3>
                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'restaurateur')
                        <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un restaurant
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Logo</th>
                            <th>Nom</th>
                            <th>Adresse</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th style="width: 200px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restaurants as $restaurant)
                            <tr>
                                <td>{{ $restaurant->id }}</td>
                                <td class="text-center">
                                    @if($restaurant->logo)
                                        <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}" class="img-thumbnail" style="max-height: 50px;">
                                    @else
                                        <span class="text-muted"><i class="fas fa-store fa-2x"></i></span>
                                    @endif
                                </td>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ $restaurant->address }}</td>
                                <td>{{ $restaurant->phone }}</td>
                                <td>{{ $restaurant->email ?? '-' }}</td>
                                <td>
                                    @if($restaurant->is_active)
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @auth
                                            @if(auth()->user()->role === 'client')
                                                <a href="{{ route('restaurants.menu', $restaurant) }}" class="btn btn-success btn-sm" title="Commander">
                                                    <i class="fas fa-shopping-basket"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'restaurateur' && auth()->user()->id === $restaurant->owner_id))
                                                <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-warning btn-sm" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('categories.index', ['restaurant' => $restaurant->id]) }}" class="btn btn-primary btn-sm" title="Gérer menu">
                                                    <i class="fas fa-utensils"></i>
                                                </a>
                                                <form action="{{ route('restaurants.destroy', $restaurant) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce restaurant ?');">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun restaurant pour l'instant.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
