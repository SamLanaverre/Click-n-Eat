<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Un admin peut modifier n'importe quel utilisateur
        // Un utilisateur peut modifier son propre profil
        // Un admin ne peut pas modifier un autre admin
        if ($model->role === 'admin' && $user->id !== $model->id) {
            return false;
        }
        
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Seul un admin peut supprimer un utilisateur
        // Un admin ne peut pas se supprimer lui-même ni un autre admin
        if ($user->id === $model->id || $model->role === 'admin') {
            return false;
        }
        
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Seul un admin peut restaurer un utilisateur
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Seul un admin peut supprimer définitivement un utilisateur
        // Un admin ne peut pas supprimer définitivement un autre admin
        if ($model->role === 'admin') {
            return false;
        }
        
        return $user->role === 'admin';
    }
}
