<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $restaurant->name }} - Menu
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Informations du restaurant -->
                    <div class="mb-8">
                        <p class="text-gray-600 dark:text-gray-400">{{ $restaurant->description }}</p>
                        <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $restaurant->address }}</span>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-phone mr-2"></i>
                            <span>{{ $restaurant->phone }}</span>
                        </div>
                    </div>

                    <!-- Menu par catégories -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($restaurant->categories as $category)
                            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    {{ $category->name }}
                                </h3>
                                <div class="space-y-4">
                                    @foreach($category->items->where('is_active', true) as $item)
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->name }}
                                                </h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ number_format($item->price, 2) }} €
                                                </p>
                                            </div>
                                            <button onclick="addToCart({{ $item->id }})" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Ajouter
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Panier flottant -->
                    <div id="cart" class="fixed bottom-4 right-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700" style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Votre commande</h3>
                        <div id="cart-items" class="space-y-2 mb-4">
                            <!-- Les items seront ajoutés ici dynamiquement -->
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="font-semibold text-gray-900 dark:text-gray-100">Total:</span>
                                <span id="cart-total" class="font-semibold text-gray-900 dark:text-gray-100">0.00 €</span>
                            </div>
                            <button onclick="submitOrder()" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                Commander
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let cart = [];
        const items = @json($restaurant->categories->flatMap->items->where('is_active', true));

        function addToCart(itemId) {
            const item = items.find(i => i.id === itemId);
            if (!item) return;

            const existingItem = cart.find(i => i.id === itemId);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: item.id,
                    name: item.name,
                    price: item.price,
                    quantity: 1
                });
            }

            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartElement = document.getElementById('cart');
            const cartItemsElement = document.getElementById('cart-items');
            const cartTotalElement = document.getElementById('cart-total');

            if (cart.length === 0) {
                cartElement.style.display = 'none';
                return;
            }

            cartElement.style.display = 'block';
            cartItemsElement.innerHTML = cart.map(item => `
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-900 dark:text-gray-100">${item.name}</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">x${item.quantity}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-900 dark:text-gray-100">${(item.price * item.quantity).toFixed(2)} €</span>
                        <button onclick="removeFromCart(${item.id})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `).join('');

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            cartTotalElement.textContent = `${total.toFixed(2)} €`;
        }

        function removeFromCart(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            updateCartDisplay();
        }

        function submitOrder() {
            if (cart.length === 0) return;

            const orderData = {
                restaurant_id: {{ $restaurant->id }},
                items: cart.map(item => ({
                    item_id: item.id,
                    quantity: item.quantity,
                    price: item.price
                }))
            };

            fetch('{{ route("orders.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = [];
                    updateCartDisplay();
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la commande.');
            });
        }
    </script>
    @endpush
</x-app-layout>
