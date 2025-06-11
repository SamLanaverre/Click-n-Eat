<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Supporte 'role:admin,restaurateur' ou plusieurs paramètres
        $userRole = $request->user()->role;
        $roles = collect($roles)->flatMap(function ($role) {
            return explode(',', $role);
        })->map(fn($r) => trim($r))->unique();

        if (!$roles->contains($userRole)) {
            // Utiliser la méthode getDashboardRoute() pour obtenir la route appropriée
            return redirect()->route($request->user()->getDashboardRoute());
        }

        return $next($request);
    }
}