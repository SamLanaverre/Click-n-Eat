@extends('layouts.app')

@section('title', isset($restaurant) ? $restaurant->name . ' - Menu' : 'Menu du restaurant')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ isset($restaurant) ? $restaurant->name . ' - Menu' : 'Menu du restaurant' }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('restaurants.show', $restaurant) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour au restaurant
            </a>
        </div>
        
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-xl font-bold mb-4">{{ $restaurant->name }} — Menu</h1>
                <div class="mb-4">
                    <p class="text-gray-600 dark:text-gray-400"> {{ $restaurant->address }}</p>
                    <p class="text-gray-600 dark:text-gray-400">{{ $restaurant->phone }}</p>
                    <p class="text-gray-600 dark:text-gray-400">{{ $restaurant->description }}</p>
                </div>
                
                <hr class="my-4 border-gray-300 dark:border-gray-700">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($restaurant->categories as $category)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow">
                            <div class="bg-gray-100 dark:bg-gray-600 px-4 py-3">
                                <h3 class="font-bold">{{ $category->name }}</h3>
                            </div>
                            <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($category->items->where('is_active', true) as $item)
                                    <li class="p-4 flex justify-between items-start">
                                        <div>
                                            <div class="font-medium">{{ $item->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->description }}</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 mb-2">
                                                {{ number_format($item->price / 100, 2, ',', ' ') }} €
                                            </span>
                                            @auth
                                                @if(auth()->user()->role === 'client')
                                                    <button onclick="addToCart({{ $item->id }})" class="mt-2 inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                        Ajouter
                                                    </button>
                                                @endif
                                            @endauth
                                        </div>
                                    </li>
                                @empty
                                    <li class="p-4 text-gray-500 dark:text-gray-400">Aucun plat actif.</li>
                                @endforelse
                            </ul>
                        </div>
                    @empty
                        <div class="col-span-2 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 p-4 rounded">
                            <p class="text-blue-700 dark:text-blue-300">Ce restaurant n'a pas encore de catégories.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
    @auth
        @if(auth()->user()->role === 'client')
        <!-- Panier flottant -->
        <div id="cart" class="fixed bottom-4 end-4 bg-white p-4 rounded shadow-lg border" style="display: none; min-width:320px; z-index:1050;">
            <h5 class="mb-3"><i class="fas fa-shopping-basket"></i> Votre commande</h5>
            <div id="cart-items" class="mb-3"></div>
            <div class="d-flex justify-content-between mb-2">
                <span>Total :</span>
                <span id="cart-total" class="fw-bold">0.00 €</span>
            </div>
            <button onclick="submitOrder()" class="btn btn-success w-100">Commander</button>
        </div>
        @endif
    @endauth
</div>
@endsection

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
            <div class='d-flex justify-content-between align-items-center mb-2'>
                <span>${item.name} <span class='badge bg-secondary ms-2'>x${item.quantity}</span></span>
                <div>
                    <span class='fw-bold'>${(item.price * item.quantity / 100).toFixed(2)} €</span>
                    <button onclick='removeFromCart(${item.id})' class='btn btn-link text-danger p-0 ms-2'><i class='fas fa-times'></i></button>
                </div>
            </div>
        `).join('');
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) / 100;
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
        fetch('{{ route('orders.store') }}', {
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
            alert('Erreur lors de la commande.');
        });
    }
</script>
@endpush
