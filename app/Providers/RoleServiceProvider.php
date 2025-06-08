<?php

namespace App\Providers;

use App\Http\Middleware\CheckRole;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class RoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CheckRole::class);
    }

    public function boot(): void
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role', CheckRole::class);
    }
}
