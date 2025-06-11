<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
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

// Routes publiques pour les restaurants
Route::controller(RestaurantController::class)->group(function () {
    Route::get('/restaurants', 'index')->name('restaurants.index');
    Route::get('/restaurants/{restaurant}', 'show')->name('restaurants.show');
    Route::get('/restaurants/{restaurant}/menu', 'showMenu')->name('restaurants.menu');
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
    
    // Gestion des catégories - protégée par policies
    Route::resource('restaurants.categories', CategoryController::class)->except(['index', 'show']);
    
    // Routes directes pour l'administration
    Route::middleware('role:admin,restaurateur')->group(function () {
        // Routes pour les catégories en administration
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Routes pour les items en administration
        Route::get('/items', [ItemController::class, 'index'])->name('items.index');
        Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
        Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    });
    
    // Gestion des items - protégée par policies
    Route::resource('categories.items', ItemController::class)->except(['index', 'show']);
    
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