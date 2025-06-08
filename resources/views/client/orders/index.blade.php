<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes Commandes
        </h2>
    </x-slot>

    <div class="space-y-6">
        @forelse($orders as $order)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold">
                                Commande #{{ $order->id }} - {{ $order->restaurant->name }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Commandé le {{ $order->created_at->format('d/m/Y à H:i') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Retrait prévu le {{ $order->pickup_time->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'ready') bg-green-100 text-green-800
                                @elseif($order->status === 'completed') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
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
                            <p class="mt-2 text-lg font-semibold">
                                {{ number_format($order->total_price, 2) }} €
                            </p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="font-medium text-gray-700 mb-2">Articles commandés :</h4>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span>{{ $item->name }} x {{ $item->pivot->quantity }}</span>
                                    <span>{{ number_format($item->pivot->price * $item->pivot->quantity, 2) }} €</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('orders.show', $order) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Détails
                        </a>
                        
                        @if(in_array($order->status, ['pending', 'confirmed']))
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500"
                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                    Annuler
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-500 text-center">
                    Vous n'avez pas encore passé de commande.
                    <a href="{{ route('restaurants.index') }}" class="text-indigo-600 hover:text-indigo-500 ml-1">
                        Découvrir les restaurants
                    </a>
                </div>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</x-client-layout>
