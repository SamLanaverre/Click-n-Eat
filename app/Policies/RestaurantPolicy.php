<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RestaurantPolicy
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
    public function viewAny(?User $user): bool
    {
        return true; // Tout le monde peut voir la liste des restaurants
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Restaurant $restaurant): bool
    {
        return true; // Tout le monde peut voir un restaurant spécifique
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'restaurateur']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Restaurant $restaurant): bool
    {
        // Admin peut tout modifier, restaurateur seulement ses propres restaurants
        return $user->role === 'restaurateur' && $restaurant->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Restaurant $restaurant): bool
    {
        // Admin peut tout supprimer, restaurateur seulement ses propres restaurants
        return $user->role === 'restaurateur' && $restaurant->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Restaurant $restaurant): bool
    {
        // Admin peut tout restaurer, restaurateur seulement ses propres restaurants
        return $user->role === 'restaurateur' && $restaurant->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Restaurant $restaurant): bool
    {
        // Seul l'admin peut supprimer définitivement
        return false; // Déjà géré par before() pour admin
    }
    
    /**
     * Determine whether the user can manage the restaurant's menu.
     */
    public function manageMenu(User $user, Restaurant $restaurant): bool
    {
        // Admin peut tout gérer, restaurateur seulement ses propres restaurants
        return $user->role === 'restaurateur' && $restaurant->user_id === $user->id;
    }
    
    /**
     * Determine whether the user can view orders for the restaurant.
     */
    public function viewOrders(User $user, Restaurant $restaurant): bool
    {
        // Admin peut tout voir, restaurateur seulement ses propres restaurants
        return $user->role === 'restaurateur' && $restaurant->user_id === $user->id;
    }
}
