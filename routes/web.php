<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantMenuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Restaurant\DashboardController as RestaurantDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route de base - affiche toujours la page de bienvenue
Route::get('/', function () {
    return view('welcome');
});

// Route de test temporaire
Route::get('/test', function () {
    return view('test');
});

// Route pour forcer la déconnexion et reset la session
Route::get('/reset-session', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

// Route pour le dashboard après connexion
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return redirect()->route($user->getDashboardRoute());
    })->name('dashboard');
});

// Routes pour les dashboards spécifiques aux rôles
Route::middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');
    
    // Client dashboard
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])
        ->middleware('role:client')
        ->name('client.dashboard');
    
    // Restaurateur dashboard
    Route::get('/restaurant/dashboard', [RestaurantDashboardController::class, 'index'])
        ->middleware('role:restaurateur')
        ->name('restaurant.dashboard');
});

// Routes publiques pour les restaurants et les catégories
Route::controller(RestaurantController::class)->group(function () {
    Route::get('/restaurants', 'index')->name('restaurants.index');
    Route::get('/restaurants/{restaurant}', 'show')->name('restaurants.show');
    Route::get('/restaurants/{restaurant}/menu', 'showMenu')->name('restaurants.menu');
});

// Routes publiques pour les catégories
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index')->name('categories.index');
    Route::get('/categories/{category}', 'show')->name('categories.show');
    Route::get('/categories/{category}/restaurants', 'restaurants')->name('categories.restaurants');
    Route::get('/categories/{category}/edit', 'edit')->name('categories.edit');
    Route::put('/categories/{category}', 'update')->name('categories.update');
});

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Profil utilisateur
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
    
    // Gestion des restaurants - protégée par policies
    Route::controller(RestaurantController::class)->group(function () {
        Route::get('/restaurants/create', 'create')->name('restaurants.create');
        Route::post('/restaurants', 'store')->name('restaurants.store');
        Route::get('/restaurants/{restaurant}/edit', 'edit')->name('restaurants.edit');
        Route::put('/restaurants/{restaurant}', 'update')->name('restaurants.update');
        Route::delete('/restaurants/{restaurant}', 'destroy')->name('restaurants.destroy');
    });
    
    // Gestion des menus de restaurant - protégée par policies
    Route::controller(RestaurantMenuController::class)->prefix('restaurants/{restaurant}/menu')->group(function () {
        Route::get('/', 'manage')->name('restaurants.menu.manage');
        Route::post('/items', 'addItem')->name('restaurants.menu.addItem');
        Route::put('/items/{item}', 'updateItem')->name('restaurants.menu.updateItem');
        Route::delete('/items/{item}', 'removeItem')->name('restaurants.menu.removeItem');
    });
    
    // Routes directes pour l'administration
    Route::middleware('role:admin,restaurateur')->group(function () {
        // Routes pour la gestion des utilisateurs (admin uniquement)
        Route::middleware('role:admin')->group(function () {
            // Routes pour les utilisateurs
            Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
            
            // Routes pour les commandes admin
            Route::get('/admin/orders', [App\Http\Controllers\OrderController::class, 'adminIndex'])->name('admin.orders.index');
        });
        
        // Routes pour les catégories globales en administration
        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/admin/categories/{category}', [CategoryController::class, 'show'])->name('admin.categories.show');
        Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        Route::post('/categories/{category}/items', [CategoryController::class, 'addItem'])->name('categories.addItem');
        Route::delete('/categories/{category}/items/{item}', [CategoryController::class, 'removeItem'])->name('categories.removeItem');
        
        // Routes pour les items globaux en administration
        Route::get('/admin/items', [ItemController::class, 'index'])->name('admin.items.index');
        Route::get('/admin/items/create', [ItemController::class, 'create'])->name('admin.items.create');
        Route::post('/admin/items', [ItemController::class, 'store'])->name('admin.items.store');
        Route::get('/admin/items/{item}', [ItemController::class, 'show'])->name('admin.items.show');
        Route::get('/admin/items/{item}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
        Route::put('/admin/items/{item}', [ItemController::class, 'update'])->name('admin.items.update');
        Route::delete('/admin/items/{item}', [ItemController::class, 'destroy'])->name('admin.items.destroy');
    });
    
    // Routes publiques pour les items
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    
    // Commandes pour les clients
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->middleware('role:client')->name('orders.index');
        Route::get('/orders/{order}', 'show')->middleware('role:client')->name('orders.show');
        Route::post('/orders', 'store')->middleware('role:client')->name('orders.store');
        Route::patch('/orders/{order}/cancel', 'cancel')->middleware('role:client')->name('orders.cancel');
    });
    
    // Commandes pour les restaurateurs
    Route::controller(OrderController::class)->prefix('restaurants/{restaurant}')->group(function () {
        Route::get('/orders', 'index')->name('restaurants.orders.index');
        Route::get('/orders/{order}', 'show')->name('restaurants.orders.show');
        Route::patch('/orders/{order}', 'update')->name('restaurants.orders.update');
    });
});

// Routes d'authentification
require __DIR__.'/auth.php';