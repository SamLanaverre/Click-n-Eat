<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            if ($request->user()) {
                // Utiliser la méthode getDashboardRoute() pour obtenir la route appropriée
                return redirect()->route($request->user()->getDashboardRoute());
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
