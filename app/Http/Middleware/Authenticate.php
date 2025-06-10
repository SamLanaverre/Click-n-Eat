<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Déconnexion automatique si la session est expirée
        if (!session()->isStarted() || !session()->has('login_web')) {
            \Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            if (!$request->expectsJson()) {
                return route('login');
            }
            return null;
        }
        if (! $request->expectsJson()) {
            return route('login');
        }
        return null;
    }
}
