<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasRole($role)) {
            if ($user) {
                return redirect()->route($user->getDashboardRoute());
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
