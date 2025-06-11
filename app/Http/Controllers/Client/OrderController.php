<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Affiche la liste des commandes du client connecté
     */
    public function index(): View
    {
        $orders = Auth::user()->orders()->with('restaurant')->latest()->paginate(10);
        
        return view('client.orders.index', [
            'orders' => $orders
        ]);
    }
    
    /**
     * Affiche le détail d'une commande
     */
    public function show(Order $order): View
    {
        // Vérifier que la commande appartient bien au client connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette commande.');
        }
        
        $order->load(['items', 'restaurant']);
        
        return view('client.orders.show', [
            'order' => $order
        ]);
    }
}
