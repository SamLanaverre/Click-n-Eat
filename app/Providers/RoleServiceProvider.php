<?php

namespace App\Providers;

use App\Http\Middleware\CheckRole;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class RoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(Kernel $kernel): void
    {
        $kernel->appendMiddlewareToGroup('web', CheckRole::class);
        $kernel->appendToMiddlewarePriority(CheckRole::class);
    }
}
