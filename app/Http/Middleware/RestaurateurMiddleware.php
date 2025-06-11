<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestaurateurMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!$request->user()) {
            // L'utilisateur n'est pas authentifié, rediriger vers la page de connexion
            return redirect()->route('login');
        }
        
        // Vérifier si l'utilisateur a le rôle restaurateur
        if ($request->user()->role !== 'restaurateur') {
            // L'utilisateur est authentifié mais n'a pas le rôle restaurateur, rediriger vers son dashboard approprié
            return redirect()->route($request->user()->getDashboardRoute());
        }

        // L'utilisateur est authentifié et a le rôle restaurateur, continuer
        return $next($request);
    }
}
