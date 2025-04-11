<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->user()) {
                // Redirect to appropriate dashboard based on actual role
                return redirect()->route(
                    $request->user()->role === 'restaurateur' ? 'restaurant.dashboard' : 'client.dashboard'
                );
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
