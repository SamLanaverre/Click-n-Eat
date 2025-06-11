<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Commandes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Liste de vos commandes</h3>
                    @forelse($orders as $order)
                        <div class="mb-6 bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-gray-800">Commande #{{ $order->id }} - {{ $order->restaurant->name }}</h3>
                                    <div>
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
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-600 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Commandé le {{ $order->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                        <p class="text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Retrait prévu le {{ $order->pickup_time->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-blue-600">{{ number_format($order->total_price, 2) }} €</p>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h4 class="text-md font-medium text-gray-800 mb-2">Articles commandés :</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($order->items as $item)
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm text-gray-800">{{ $item->name }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-800">{{ $item->pivot->quantity }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-800 text-right">{{ number_format($item->pivot->price * $item->pivot->quantity, 2) }} €</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right">
                                <a href="{{ route('client.orders.show', $order) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Détails
                                </a>
                                
                                @if(in_array($order->status, ['pending', 'confirmed']))
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Annuler
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-blue-50 p-4 rounded-md">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-blue-700">Vous n'avez pas encore passé de commande.</span>
                                <a href="{{ route('restaurants.index') }}" class="ml-1 text-blue-800 hover:underline font-medium">
                                    Découvrir les restaurants
                                </a>
                            </div>
                        </div>
                    @endforelse
                    
                    <div class="mt-6 flex justify-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
