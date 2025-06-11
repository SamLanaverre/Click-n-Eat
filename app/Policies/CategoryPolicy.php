<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
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
        return true; // Tout le monde peut voir les catégories
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Category $category): bool
    {
        return true; // Tout le monde peut voir une catégorie spécifique
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Vérifie si l'utilisateur est un restaurateur et propriétaire du restaurant
        if ($user->role === 'restaurateur') {
            // La logique pour vérifier si le restaurant appartient à l'utilisateur
            // sera gérée dans le contrôleur car nous n'avons pas accès au restaurant ici
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        // Restaurateur peut modifier ses propres catégories
        if ($user->role === 'restaurateur') {
            return $category->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        // Restaurateur peut supprimer ses propres catégories
        if ($user->role === 'restaurateur') {
            return $category->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        // Restaurateur peut restaurer ses propres catégories
        if ($user->role === 'restaurateur') {
            return $category->restaurant->user_id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        // Seul l'admin peut supprimer définitivement
        return false; // Déjà géré par before() pour admin
    }
}
