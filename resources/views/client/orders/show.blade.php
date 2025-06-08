<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Commande #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Informations de la commande</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Restaurant :</dt>
                            <dd class="font-medium">{{ $order->restaurant->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Date de commande :</dt>
                            <dd>{{ $order->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Heure de retrait :</dt>
                            <dd>{{ $order->pickup_time->format('d/m/Y à H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Statut :</dt>
                            <dd>
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
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Adresse du restaurant</h3>
                    <p class="text-gray-600">
                        {{ $order->restaurant->address }}<br>
                        {{ $order->restaurant->postal_code }} {{ $order->restaurant->city }}
                    </p>
                    
                    @if($order->restaurant->phone)
                        <p class="mt-2">
                            <span class="text-gray-600">Téléphone :</span>
                            <a href="tel:{{ $order->restaurant->phone }}" class="text-indigo-600 hover:text-indigo-500">
                                {{ $order->restaurant->phone }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Détails de la commande -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Articles commandés</h3>
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Article
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantité
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Prix unitaire
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ $item->pivot->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ number_format($item->pivot->price, 2) }} €
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                        {{ number_format($item->pivot->price * $item->pivot->quantity, 2) }} €
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    Total
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                    {{ number_format($order->total_price, 2) }} €
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('orders.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Retour aux commandes
                </a>
                
                @if(in_array($order->status, ['pending', 'confirmed']))
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500"
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                            Annuler la commande
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>
