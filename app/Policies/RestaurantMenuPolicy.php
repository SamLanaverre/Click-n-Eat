<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Item;
use Illuminate\Auth\Access\Response;

class RestaurantMenuPolicy
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
     * Détermine si l'utilisateur peut voir le menu d'un restaurant.
     */
    public function viewMenu(?User $user, Restaurant $restaurant): bool
    {
        return true; // Tout le monde peut voir le menu d'un restaurant
    }
    
    /**
     * Détermine si l'utilisateur peut gérer le menu d'un restaurant.
     */
    public function manageMenu(User $user, Restaurant $restaurant): bool
    {
        // Un restaurateur peut gérer le menu de son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
    
    /**
     * Détermine si l'utilisateur peut ajouter un item au menu d'un restaurant.
     */
    public function addItem(User $user, Restaurant $restaurant, Item $item): bool
    {
        // Un restaurateur peut ajouter un item au menu de son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
    
    /**
     * Détermine si l'utilisateur peut mettre à jour un item dans le menu d'un restaurant.
     */
    public function updateItem(User $user, Restaurant $restaurant, Item $item): bool
    {
        // Un restaurateur peut mettre à jour un item dans le menu de son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
    
    /**
     * Détermine si l'utilisateur peut supprimer un item du menu d'un restaurant.
     */
    public function removeItem(User $user, Restaurant $restaurant, Item $item): bool
    {
        // Un restaurateur peut supprimer un item du menu de son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
    
    /**
     * Détermine si l'utilisateur peut modifier le prix d'un item dans le menu d'un restaurant.
     */
    public function updatePrice(User $user, Restaurant $restaurant, Item $item): bool
    {
        // Un restaurateur peut modifier le prix d'un item dans le menu de son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
    
    /**
     * Détermine si l'utilisateur peut modifier la disponibilité d'un item dans le menu d'un restaurant.
     */
    public function toggleAvailability(User $user, Restaurant $restaurant, Item $item): bool
    {
        // Un restaurateur peut modifier la disponibilité d'un item dans le menu de son propre restaurant
        if ($user->role === 'restaurateur') {
            return $restaurant->user_id === $user->id;
        }
        
        return false;
    }
}
