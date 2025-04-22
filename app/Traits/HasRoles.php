<?php

namespace App\Traits;

trait HasRoles
{
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isRestaurateur()
    {
        return $this->hasRole('restaurateur');
    }

    public function isClient()
    {
        return $this->hasRole('client');
    }

    public function getDashboardRoute()
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'restaurateur' => 'restaurateur.dashboard',
            'client' => 'client.dashboard',
            default => 'login',
        };
    }
}
