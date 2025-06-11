<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Auth\Access\Response;

class ItemPolicy
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
        return true; // Tout le monde peut voir les items
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Item $item): bool
    {
        return true; // Tout le monde peut voir un item spécifique
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les administrateurs peuvent créer des items globaux
        // (déjà géré par la méthode before)
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Item $item): bool
    {
        // Seuls les administrateurs peuvent modifier des items globaux
        // (déjà géré par la méthode before)
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Item $item): bool
    {
        // Seuls les administrateurs peuvent supprimer des items globaux,
        // et seulement s'ils ne sont pas utilisés par des restaurants
        // La vérification de l'utilisation sera faite dans le contrôleur
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Item $item): bool
    {
        // Seuls les administrateurs peuvent restaurer des items globaux
        // (déjà géré par la méthode before)
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Item $item): bool
    {
        // Seuls les administrateurs peuvent supprimer définitivement des items globaux
        // (déjà géré par la méthode before)
        return false;
    }
    
    /**
     * Determine whether the user can add an item to a restaurant menu.
     */
    public function addToRestaurant(User $user, Item $item, Restaurant $restaurant): bool
    {
        // Un restaurateur peut ajouter un item global à son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
    
    /**
     * Determine whether the user can update an item in a restaurant menu.
     */
    public function updateInRestaurant(User $user, Item $item, Restaurant $restaurant): bool
    {
        // Un restaurateur peut mettre à jour un item dans son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
    
    /**
     * Determine whether the user can remove an item from a restaurant menu.
     */
    public function removeFromRestaurant(User $user, Item $item, Restaurant $restaurant): bool
    {
        // Un restaurateur peut retirer un item de son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
}
