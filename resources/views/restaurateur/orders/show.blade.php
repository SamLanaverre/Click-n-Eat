<x-adminlte-layout>
    @section('header', 'Détails de la commande #' . $order->id)

    @section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Informations de la commande -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la commande</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Client</dt>
                        <dd class="col-sm-8">{{ $order->client->name }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $order->client->email }}</dd>

                        <dt class="col-sm-4">Date de commande</dt>
                        <dd class="col-sm-8">{{ $order->created_at->format('d/m/Y à H:i') }}</dd>

                        <dt class="col-sm-4">Heure de retrait</dt>
                        <dd class="col-sm-8">{{ $order->pickup_time->format('d/m/Y à H:i') }}</dd>

                        <dt class="col-sm-4">Statut</dt>
                        <dd class="col-sm-8">
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
                        </dd>

                        <dt class="col-sm-4">Total</dt>
                        <dd class="col-sm-8">{{ number_format($order->total_price, 2) }} €</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Actions sur la commande -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    @if($order->status === 'pending')
                        <form action="{{ route('orders.update', $order) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn btn-success btn-lg btn-block mb-3">
                                <i class="fas fa-check mr-2"></i> Confirmer la commande
                            </button>
                        </form>
                    @elseif($order->status === 'confirmed')
                        <form action="{{ route('orders.update', $order) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="btn btn-primary btn-lg btn-block mb-3">
                                <i class="fas fa-utensils mr-2"></i> Marquer comme prête
                            </button>
                        </form>
                    @elseif($order->status === 'ready')
                        <form action="{{ route('orders.update', $order) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success btn-lg btn-block mb-3">
                                <i class="fas fa-check-double mr-2"></i> Terminer la commande
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails des articles -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Articles commandés</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-right">Prix unitaire</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-center">{{ $item->pivot->quantity }}</td>
                            <td class="text-right">{{ number_format($item->pivot->price, 2) }} €</td>
                            <td class="text-right">{{ number_format($item->pivot->price * $item->pivot->quantity, 2) }} €</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Total</th>
                        <th class="text-right">{{ number_format($order->total_price, 2) }} €</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endsection
</x-adminlte-layout>
