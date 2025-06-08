<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Restaurant $restaurant = null)
    {
        $user = Auth::user();
        
        if ($restaurant) {
            // Route pour restaurateur
            if (!$user->hasRole('restaurateur') || $restaurant->owner_id !== $user->id) {
                return abort(403);
            }
            
            $orders = Order::with(['client', 'items'])
                ->where('restaurant_id', $restaurant->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('restaurateur.orders.index', compact('orders', 'restaurant'));
        } else {
            // Route pour client
            if (!$user->hasRole('client')) {
                return abort(403);
            }
            
            $orders = Order::with(['restaurant', 'items'])
                ->where('client_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('client.orders.index', compact('orders'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $restaurant = Restaurant::findOrFail($request->restaurant_id);
        $categories = $restaurant->categories()->with('items')->get();
        
        return view('client.orders.create', compact('restaurant', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'pickup_time' => 'required|date|after:now'
        ]);

        try {
            DB::beginTransaction();
            
            $total = 0;
            foreach ($request->items as $item) {
                $menuItem = Item::findOrFail($item['id']);
                $total += $menuItem->price * $item['quantity'];
            }

            $order = Order::create([
                'client_id' => Auth::id(),
                'restaurant_id' => $request->restaurant_id,
                'total_price' => $total,
                'status' => 'pending',
                'pickup_time' => $request->pickup_time
            ]);

            foreach ($request->items as $item) {
                $menuItem = Item::findOrFail($item['id']);
                $order->items()->attach($menuItem->id, [
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price
                ]);
            }

            DB::commit();

            // TODO: Envoyer une notification au restaurateur
            
            return redirect()->route('orders.show', $order)
                ->with('status', 'Votre commande a été enregistrée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la création de votre commande.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Restaurant $restaurant = null, Order $order = null)
    {
        if (!$order) {
            $order = Order::findOrFail($request->route('order'));
        }
        
        $order->load(['restaurant', 'items', 'client']);
        $user = Auth::user();
        
        if ($restaurant) {
            // Route pour restaurateur
            if (!$user->hasRole('restaurateur') || 
                $restaurant->owner_id !== $user->id || 
                $order->restaurant_id !== $restaurant->id) {
                return abort(403);
            }
            return view('restaurateur.orders.show', compact('order', 'restaurant'));
        } else {
            // Route pour client
            if (!$user->hasRole('client') || $order->client_id !== $user->id) {
                return abort(403);
            }
            return view('client.orders.show', compact('order'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant, Order $order)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('restaurateur') || 
            $restaurant->owner_id !== $user->id || 
            $order->restaurant_id !== $restaurant->id) {
            return abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,ready,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        // TODO: Envoyer une notification au client
        
        return back()->with('status', 'Le statut de la commande a été mis à jour.');
    }

    /**
     * Cancel an order (client only)
     */
    public function cancel(Order $order)
    {
        if ($order->client_id !== Auth::id()) {
            return abort(403);
        }
        
        if ($order->status !== 'pending') {
            return abort(403, 'Seules les commandes en attente peuvent être annulées.');
        }
        
        $order->update(['status' => 'cancelled']);
        
        return back()->with('status', 'La commande a été annulée.');
    }
}
