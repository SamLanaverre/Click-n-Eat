<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\AuthServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enregistre notre AuthServiceProvider
        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}