@extends('layout.adminlte')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Gestion des commandes</h3>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <ul class="nav nav-tabs mb-3" id="ordersTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="current-tab" data-toggle="tab" href="#current" role="tab" aria-controls="current" aria-selected="true">
                        Commandes en cours
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="past-tab" data-toggle="tab" href="#past" role="tab" aria-controls="past" aria-selected="false">
                        Commandes passées
                    </a>
                </li>
            </ul>
            
            <div class="tab-content" id="ordersTabContent">
                <div class="tab-pane fade show active" id="current" role="tabpanel" aria-labelledby="current-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Restaurant</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($currentOrders ?? [] as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->restaurant->name }}</td>
                                        <td>{{ number_format($order->total_amount, 2) }} €</td>
                                        <td>
                                            <span class="badge badge-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'accepted' ? 'info' : ($order->status === 'preparing' ? 'primary' : ($order->status === 'ready' ? 'success' : 'secondary'))) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Détails
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucune commande en cours</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($currentOrders))
                        <div class="mt-4">
                            {{ $currentOrders->links() }}
                        </div>
                    @endif
                </div>
                
                <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Restaurant</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pastOrders ?? [] as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->restaurant->name }}</td>
                                        <td>{{ number_format($order->total_amount, 2) }} €</td>
                                        <td>
                                            <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Détails
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucune commande passée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($pastOrders))
                        <div class="mt-4">
                            {{ $pastOrders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
