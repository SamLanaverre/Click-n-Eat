<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        $user = $request->user();

        // Si aucun rôle n'est spécifié, on laisse passer
        if ($role === null) {
            return $next($request);
        }

        if (!$user || $user->role !== $role) {
            if ($user) {
                return redirect()->route($user->getDashboardRoute());
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}