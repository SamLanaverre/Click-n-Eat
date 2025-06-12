@extends('layout.adminlte')

@section('header', 'Tableau de bord Restaurateur')

@section('content')
<div class="row mb-3">
    <div class="col-12 text-right">
        <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Ajouter un restaurant
        </a>
    </div>
</div>

@if($restaurants->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-2"></i> Vous n'avez pas encore de restaurant. Créez-en un pour commencer.
    </div>
@else
    <div class="row">
        @foreach ($restaurants as $restaurant)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0">{{ $restaurant->name }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ Str::limit($restaurant->description, 100) }}</p>
                        <p class="text-muted mb-0"><i class="fas fa-map-marker-alt mr-1"></i> {{ $restaurant->address }}</p>
                        <p class="text-muted"><i class="fas fa-phone mr-1"></i> {{ $restaurant->phone }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="badge badge-info mr-1">
                                    <i class="fas fa-utensils mr-1"></i> {{ $restaurant->categories->count() }} catégories
                                </span>
                                <span class="badge badge-success">
                                    <i class="fas fa-shopping-cart mr-1"></i> {{ $restaurant->orders->count() }} commandes aujourd'hui
                                </span>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between">
                            <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit mr-1"></i> Modifier
                            </a>
                            <a href="{{ route('restaurants.menu.index', $restaurant) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-utensils mr-1"></i> Gérer le menu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Commandes récentes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Commandes du jour</h3>
                </div>
                <div class="card-body p-0">
                    @if(count($restaurants->flatMap->orders) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Restaurant</th>
                                        <th>Client</th>
                                        <th>Heure de retrait</th>
                                        <th>Statut</th>
                                        <th>Total</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($restaurants as $restaurant)
                                        @foreach($restaurant->orders as $order)
                                            <tr>
                                                <td>{{ $restaurant->name }}</td>
                                                <td>{{ $order->client->name }}</td>
                                                <td>{{ $order->pickup_time->format('H:i') }}</td>
                                                <td>
                                                    @if($order->status === 'pending')
                                                        <span class="badge badge-warning">En attente</span>
                                                    @elseif($order->status === 'confirmed')
                                                        <span class="badge badge-info">Confirmée</span>
                                                    @elseif($order->status === 'ready')
                                                        <span class="badge badge-success">Prête</span>
                                                    @elseif($order->status === 'completed')
                                                        <span class="badge badge-secondary">Terminée</span>
                                                    @else
                                                        <span class="badge badge-danger">Annulée</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($order->total_price, 2) }} €</td>
                                                <td class="text-right">
                                                    <a href="{{ route('restaurants.orders.show', [$restaurant, $order]) }}" class="btn btn-xs btn-info">
                                                        <i class="fas fa-eye"></i> Détails
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light m-3">Aucune commande aujourd'hui</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
