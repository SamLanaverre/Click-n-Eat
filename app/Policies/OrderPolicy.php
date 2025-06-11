<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true; // Admin peut tout faire
        }
        
        return null; // Continuer avec les vérifications spécifiques
    }
    
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin peut tout voir, restaurateur peut voir les commandes de ses restaurants,
        // client peut voir ses propres commandes
        return in_array($user->role, ['admin', 'restaurateur', 'client']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Client peut voir ses propres commandes
        if ($user->role === 'client') {
            return $order->user_id === $user->id;
        }
        
        // Restaurateur peut voir les commandes de ses restaurants
        if ($user->role === 'restaurateur') {
            return $order->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les clients peuvent créer des commandes
        return $user->role === 'client';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Restaurateur peut mettre à jour le statut des commandes de ses restaurants
        if ($user->role === 'restaurateur') {
            return $order->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        // Client peut annuler sa commande si elle n'est pas encore en préparation
        if ($user->role === 'client' && $order->user_id === $user->id) {
            return in_array($order->status, ['pending', 'received']);
        }
        
        // Restaurateur peut annuler une commande de son restaurant
        if ($user->role === 'restaurateur') {
            return $order->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        // Seul l'admin peut restaurer des commandes supprimées
        return false; // Déjà géré par before() pour admin
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        // Seul l'admin peut supprimer définitivement
        return false; // Déjà géré par before() pour admin
    }
    
    /**
     * Determine whether the user can update the status of the order.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        // Restaurateur peut mettre à jour le statut des commandes de ses restaurants
        if ($user->role === 'restaurateur') {
            return $order->restaurant->user_id === $user->id;
        }
        
        return false;
    }
}
