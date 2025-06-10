<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bienvenue chez ClicknEat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Restaurants disponibles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($restaurants as $restaurant)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold">{{ $restaurant->name }}</h3>
                            <p class="text-gray-600">{{ $restaurant->description }}</p>
                                {{ $restaurant->description }}
                            </p>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-map-marker-alt"></i> {{ $restaurant->address }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-phone"></i> {{ $restaurant->phone }}
                                </p>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('restaurants.menu', $restaurant) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Voir le menu
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
