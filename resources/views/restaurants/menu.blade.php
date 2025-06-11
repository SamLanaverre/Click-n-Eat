@extends('layout.adminlte')

@section('content')
<div class="container py-4">
    <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour à la fiche</a>
    <div class="card mb-4">
        <div class="card-header">
            <h1 class="h4 mb-0">{{ $restaurant->name }} — Menu</h1>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <span class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $restaurant->address }}</span><br>
                <span class="text-muted"><i class="fas fa-phone"></i> {{ $restaurant->phone }}</span><br>
                <span class="text-muted">{{ $restaurant->description }}</span>
            </div>
            <hr>
            <div class="row">
                @forelse($restaurant->categories as $category)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <strong>{{ $category->name }}</strong>
                            </div>
                            <ul class="list-group list-group-flush">
                                @forelse($category->items->where('is_active', true) as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-bold">{{ $item->name }}</div>
                                            <div class="small text-muted">{{ $item->description }}</div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary mb-2">{{ number_format($item->price / 100, 2, ',', ' ') }} €</span><br>
                                            @auth
                                                @if(auth()->user()->role === 'client')
                                                    <button onclick="addToCart({{ $item->id }})" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Ajouter</button>
                                                @endif
                                            @endauth
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">Aucun plat actif.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">Aucune catégorie/menu disponible.</div>
                @endforelse
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
