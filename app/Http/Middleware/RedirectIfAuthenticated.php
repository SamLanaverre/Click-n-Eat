<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
    // Vérifie que la session n'est pas expirée
    if (!session()->isStarted() || !session()->has('login_web')) {
        Auth::guard($guard)->logout();
        session()->invalidate();
        session()->regenerateToken();
        continue;
    }
    // Utilisateur authentifié, rediriger en fonction du rôle
    $user = Auth::guard($guard)->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } else if ($user->isRestaurateur()) {
        return redirect()->route('restaurateur.dashboard');
    } else {
        return redirect()->route('client.dashboard');
    }
}
        }

        return $next($request);
    }
}