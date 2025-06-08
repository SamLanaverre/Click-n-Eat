<x-adminlte-layout>
    @section('header', 'Gestion des Commandes')

    @section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des commandes</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Heure de retrait</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->client->name }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $order->pickup_time->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($order->total_price, 2) }} €</td>
                                <td>
                                    <span class="badge
                                        @if($order->status === 'pending') badge-warning
                                        @elseif($order->status === 'confirmed') badge-info
                                        @elseif($order->status === 'ready') badge-success
                                        @elseif($order->status === 'completed') badge-secondary
                                        @else badge-danger
                                        @endif">
                                        @switch($order->status)
                                            @case('pending')
                                                En attente
                                                @break
                                            @case('confirmed')
                                                Confirmée
                                                @break
                                            @case('ready')
                                                Prête
                                                @break
                                            @case('completed')
                                                Terminée
                                                @break
                                            @case('cancelled')
                                                Annulée
                                                @break
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('restaurants.orders.show', [$restaurant, $order]) }}" 
                                           class="btn btn-sm btn-info"
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status === 'pending')
                                            <form action="{{ route('restaurants.orders.update', [$restaurant, $order]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-sm btn-success" title="Confirmer">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @elseif($order->status === 'confirmed')
                                            <form action="{{ route('restaurants.orders.update', [$restaurant, $order]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="ready">
                                                <button type="submit" class="btn btn-sm btn-primary" title="Marquer comme prête">
                                                    <i class="fas fa-utensils"></i>
                                                </button>
                                            </form>
                                        @elseif($order->status === 'ready')
                                            <form action="{{ route('restaurants.orders.update', [$restaurant, $order]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-sm btn-success" title="Terminer">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucune commande à afficher</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
    @endsection
</x-adminlte-layout>
