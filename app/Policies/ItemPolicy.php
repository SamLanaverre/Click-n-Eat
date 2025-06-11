<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
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
        // Vérifie si l'utilisateur est un restaurateur
        // La vérification de propriété du restaurant sera faite dans le contrôleur
        return $user->role === 'restaurateur';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Item $item): bool
    {
        // Restaurateur peut modifier ses propres items
        if ($user->role === 'restaurateur') {
            return $item->category->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Item $item): bool
    {
        // Restaurateur peut supprimer ses propres items
        if ($user->role === 'restaurateur') {
            return $item->category->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Item $item): bool
    {
        // Restaurateur peut restaurer ses propres items
        if ($user->role === 'restaurateur') {
            return $item->category->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Item $item): bool
    {
        // Seul l'admin peut supprimer définitivement
        return false; // Déjà géré par before() pour admin
    }
    
    /**
     * Determine whether the user can toggle the active status of the item.
     */
    public function toggleActive(User $user, Item $item): bool
    {
        // Restaurateur peut activer/désactiver ses propres items
        if ($user->role === 'restaurateur') {
            return $item->category->restaurant->user_id === $user->id;
        }
        
        return false;
    }
}
