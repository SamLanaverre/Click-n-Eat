@extends('layout.adminlte')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Menu du restaurant</h1>
        <a href="{{ route('restaurants.items.index', $restaurant) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Gérer les items
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($menuItems->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i> Votre menu est vide. Ajoutez des items depuis la page de gestion des items.
        </div>
    @else
        <div class="row">
            @foreach($categories as $category)
                @if($category->items->where('is_in_menu', true)->count() > 0)
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0">{{ $category->name }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 80px">Image</th>
                                                <th>Nom</th>
                                                <th>Description</th>
                                                <th>Prix</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category->items->where('is_in_menu', true)->where('restaurant_id', $restaurant->id) as $item)
                                                <tr>
                                                    <td>
                                                        @if($item->image)
                                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-thumbnail" style="max-height: 50px;">
                                                        @else
                                                            <span class="text-muted"><i class="fas fa-image"></i></span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ Str::limit($item->description, 100) }}</td>
                                                    <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                                                    <td>
                                                        <form action="{{ route('restaurants.menu.remove', [$restaurant, $item]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Retirer du menu">
                                                                <i class="fas fa-minus-circle"></i> Retirer du menu
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
