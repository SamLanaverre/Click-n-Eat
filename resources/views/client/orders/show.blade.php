<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détail de la commande #' . $order->id) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Détails de la commande -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Détails de la commande</h3>
                    </div>
                    <div class="p-4">
                        <div class="divide-y divide-gray-200">
                            <div class="py-3 flex justify-between">
                                <span class="font-medium text-gray-700">Restaurant</span>
                                <span class="font-semibold text-gray-900">{{ $order->restaurant->name }}</span>
                            </div>
                            <div class="py-3 flex justify-between">
                                <span class="font-medium text-gray-700">Date de commande</span>
                                <span class="text-gray-900">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="py-3 flex justify-between">
                                <span class="font-medium text-gray-700">Heure de retrait</span>
                                <span class="text-gray-900">{{ $order->pickup_time->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="py-3 flex justify-between items-center">
                                <span class="font-medium text-gray-700">Statut</span>
                                @php
                                    $badgeClass = 'bg-gray-500 text-white';
                                    $statusText = 'Inconnu';
                                    
                                    switch($order->status) {
                                        case 'pending':
                                            $badgeClass = 'bg-yellow-500 text-white';
                                            $statusText = 'En attente';
                                            break;
                                        case 'confirmed':
                                            $badgeClass = 'bg-blue-500 text-white';
                                            $statusText = 'Confirmée';
                                            break;
                                        case 'ready':
                                            $badgeClass = 'bg-green-500 text-white';
                                            $statusText = 'Prête';
                                            break;
                                        case 'completed':
                                            $badgeClass = 'bg-gray-500 text-white';
                                            $statusText = 'Terminée';
                                            break;
                                        case 'cancelled':
                                            $badgeClass = 'bg-red-500 text-white';
                                            $statusText = 'Annulée';
                                            break;
                                    }
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ $statusText }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Adresse du restaurant -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Adresse du restaurant</h3>
                    </div>
                    <div class="p-4">
                        <div class="text-gray-800">
                            <p class="font-semibold mb-2">{{ $order->restaurant->name }}</p>
                            <p class="mb-1">{{ $order->restaurant->address }}</p>
                            <p class="mb-3">{{ $order->restaurant->postal_code }} {{ $order->restaurant->city }}</p>
                            @if($order->restaurant->phone)
                                <p class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <a href="tel:{{ $order->restaurant->phone }}" class="text-blue-600 hover:underline">{{ $order->restaurant->phone }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles commandés -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Articles commandés</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $item->pivot->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($item->pivot->price, 2) }} €</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-right">{{ number_format($item->pivot->price * $item->pivot->quantity, 2) }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Total</th>
                                <th class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($order->total_price, 2) }} €</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 border-t border-gray-200">
                    <a href="{{ route('client.orders') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour aux commandes
                    </a>
                    
                    @if(in_array($order->status, ['pending', 'confirmed']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Annuler la commande
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
