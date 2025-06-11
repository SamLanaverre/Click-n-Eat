<?php

namespace App\Providers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Policies\RestaurantPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ItemPolicy;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Restaurant::class => RestaurantPolicy::class,
        Category::class => CategoryPolicy::class,
        Item::class => ItemPolicy::class,
        Order::class => OrderPolicy::class,
        User::class => UserPolicy::class,
    ];
    
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enregistre automatiquement toutes les policies définies dans $policies
        $this->registerPolicies();
        
        // Définit des gates personnalisés pour les fonctionnalités spécifiques
        Gate::define('manage-restaurant', function ($user, $restaurant) {
            return $user->role === 'admin' || 
                  ($user->role === 'restaurateur' && $restaurant->user_id === $user->id);
        });
        
        Gate::define('manage-menu', function ($user, $restaurant) {
            return $user->role === 'admin' || 
                  ($user->role === 'restaurateur' && $restaurant->user_id === $user->id);
        });
        
        Gate::define('view-orders', function ($user, $restaurant = null) {
            if ($user->role === 'admin') {
                return true;
            }
            
            if ($user->role === 'restaurateur' && $restaurant) {
                return $restaurant->user_id === $user->id;
            }
            
            return false;
        });
    }
}
