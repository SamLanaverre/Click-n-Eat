<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Commander chez {{ $restaurant->name }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('orders.store') }}" class="p-6" x-data="orderForm()">
            @csrf
            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

            <!-- Menu par catégories -->
            @foreach($categories as $category)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">{{ $category->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($category->items as $item)
                            <div class="bg-white rounded-lg shadow p-4">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" 
                                         class="w-full h-48 object-cover rounded-lg mb-4">
                                @endif
                                <h4 class="font-semibold">{{ $item->name }}</h4>
                                <p class="text-gray-600 text-sm mb-2">{{ $item->description }}</p>
                                <p class="text-lg font-semibold mb-3">{{ number_format($item->price, 2) }} €</p>
                                
                                <div class="flex items-center space-x-4">
                                    <button type="button" 
                                            class="px-2 py-1 bg-gray-200 rounded-lg"
                                            @click="decrementQuantity({{ $item->id }})">
                                        -
                                    </button>
                                    <input type="number" 
                                           name="items[{{ $item->id }}][quantity]"
                                           x-model="quantities[{{ $item->id }}]"
                                           class="w-16 text-center border-gray-300 rounded-md"
                                           min="0"
                                           readonly>
                                    <button type="button"
                                            class="px-2 py-1 bg-gray-200 rounded-lg"
                                            @click="incrementQuantity({{ $item->id }})">
                                        +
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Résumé de la commande -->
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Résumé de votre commande</h3>
                <div x-show="hasItems" class="space-y-4">
                    <template x-for="item in selectedItems" :key="item.id">
                        <div class="flex justify-between items-center">
                            <div>
                                <span x-text="item.name"></span>
                                <span class="text-gray-600">
                                    x<span x-text="quantities[item.id]"></span>
                                </span>
                            </div>
                            <span x-text="formatPrice(item.price * quantities[item.id])"></span>
                        </div>
                    </template>
                    
                    <div class="border-t pt-4 flex justify-between font-semibold">
                        <span>Total</span>
                        <span x-text="formatPrice(total)"></span>
                    </div>
                </div>
                <div x-show="!hasItems" class="text-gray-600">
                    Votre panier est vide
                </div>
            </div>

            <!-- Heure de retrait -->
            <div class="mt-6">
                <label for="pickup_time" class="block text-sm font-medium text-gray-700">
                    Heure de retrait
                </label>
                <input type="datetime-local" 
                       name="pickup_time" 
                       id="pickup_time"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required
                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}"
                       max="{{ now()->addDays(7)->format('Y-m-d\TH:i') }}">
            </div>

            <!-- Bouton de commande -->
            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors"
                        x-bind:disabled="!hasItems"
                        x-bind:class="{'opacity-50 cursor-not-allowed': !hasItems}">
                    Commander
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function orderForm() {
            return {
                quantities: {},
                items: @json($categories->pluck('items')->flatten()),
                
                init() {
                    this.items.forEach(item => {
                        this.quantities[item.id] = 0;
                    });
                },
                
                incrementQuantity(itemId) {
                    this.quantities[itemId]++;
                },
                
                decrementQuantity(itemId) {
                    if (this.quantities[itemId] > 0) {
                        this.quantities[itemId]--;
                    }
                },
                
                get selectedItems() {
                    return this.items.filter(item => this.quantities[item.id] > 0);
                },
                
                get hasItems() {
                    return this.selectedItems.length > 0;
                },
                
                get total() {
                    return this.selectedItems.reduce((sum, item) => {
                        return sum + (item.price * this.quantities[item.id]);
                    }, 0);
                },
                
                formatPrice(price) {
                    return new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'EUR'
                    }).format(price);
                }
            }
        }
    </script>
    @endpush
</x-client-layout>
