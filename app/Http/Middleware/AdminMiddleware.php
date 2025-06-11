<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!$request->user()) {
            // L'utilisateur n'est pas authentifié, rediriger vers la page de connexion
            return redirect()->route('login');
        }
        
        // Vérifier si l'utilisateur a le rôle admin
        if ($request->user()->role !== 'admin') {
            // L'utilisateur est authentifié mais n'a pas le rôle admin, rediriger vers son dashboard approprié
            return redirect()->route($request->user()->getDashboardRoute());
        }

        // L'utilisateur est authentifié et a le rôle admin, continuer
        return $next($request);
    }
}
