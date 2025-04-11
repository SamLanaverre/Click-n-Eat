<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tableau de bord Restaurateur') }}
            </h2>
            <a href="{{ route('restaurants.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Ajouter un restaurant
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($restaurants as $restaurant)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $restaurant->name }}
                                </h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">
                                    {{ $restaurant->description }}
                                </p>
                            </div>
                            <div class="flex space-x-4">
                                <a href="{{ route('restaurants.edit', $restaurant) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Modifier
                                </a>
                                <a href="{{ route('restaurants.categories.index', $restaurant) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Gérer le menu
                                </a>
                            </div>
                        </div>

                        <!-- Commandes du jour -->
                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                Commandes du jour
                            </h4>
                            @if($restaurant->orders->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Client
                                                </th>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Heure de retrait
                                                </th>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Statut
                                                </th>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Total
                                                </th>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($restaurant->orders as $order)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $order->client->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $order->pickup_time->format('H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                                            @elseif($order->status === 'ready') bg-green-100 text-green-800
                                                            @elseif($order->status === 'completed') bg-gray-100 text-gray-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {{ number_format($order->total_price, 2) }} €
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('restaurants.orders.show', [$restaurant, $order]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                            Détails
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">Aucune commande aujourd'hui</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($restaurants->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">Vous n'avez pas encore de restaurant</p>
                        <a href="{{ route('restaurants.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Ajouter votre premier restaurant
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
