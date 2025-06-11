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
        // Seuls les administrateurs peuvent créer des catégories globales
        // (déjà géré par la méthode before)
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        // Seuls les administrateurs peuvent modifier des catégories globales
        // (déjà géré par la méthode before)
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        // Seuls les administrateurs peuvent supprimer des catégories globales,
        // et seulement si elles ne sont pas utilisées par des items
        // La vérification de l'utilisation sera faite dans le contrôleur
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        // Seuls les administrateurs peuvent restaurer des catégories globales
        // (déjà géré par la méthode before)
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        // Seuls les administrateurs peuvent supprimer définitivement des catégories globales
        // (déjà géré par la méthode before)
        return false;
    }
    
    /**
     * Détermine si l'utilisateur peut voir les restaurants proposant des items de cette catégorie.
     */
    public function viewRestaurants(?User $user, Category $category): bool
    {
        return true; // Tout le monde peut voir les restaurants par catégorie
    }
}
