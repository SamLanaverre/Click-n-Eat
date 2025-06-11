<x-client-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tableau de bord</h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6 text-gray-900">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Restaurants populaires</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($restaurants as $restaurant)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $restaurant->name }}</h3>
                                <p class="text-gray-600 mb-3">{{ $restaurant->description }}</p>
                                <div class="mb-2 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $restaurant->address }}
                                </div>
                                <div class="mb-4 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $restaurant->phone }}
                                </div>
                                @php
                                    try {
                                        $menuUrl = route('restaurants.menu', $restaurant);
                                    } catch (\Exception $e) {
                                        $menuUrl = route('restaurants.show', $restaurant);
                                    }
                                @endphp
                                <div class="mt-4">
                                    <a href="{{ $menuUrl }}" style="background-color: #2563eb; color: white; padding: 10px 20px; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Voir le menu
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 bg-blue-50 p-4 rounded-md">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-blue-700">Aucun restaurant disponible pour le moment.</span>
                            </div>
                        </div>
                    @endforelse
                </div>
        </div>
    </div>
</x-client-layout>
