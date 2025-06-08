<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestaurateurMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== 'restaurateur') {
            if ($request->user()) {
                return redirect()->route($request->user()->role . '.dashboard');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
