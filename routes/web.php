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
use App\Http\Middleware\CheckRole;

// Route de base - redirige vers login
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    if (Auth::check() && Session::has('login_web')) {
        // Session valide, rediriger selon le rôle
        $user = Auth::user();
        return redirect()->route($user->getDashboardRoute());
    }
    // Si la session est expirée, on logout pour éviter la redirection auto
    Auth::logout();
    Session::invalidate();
    Session::regenerateToken();
    return redirect()->route('login');
});

// Route pour le dashboard après connexion
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        // Utilisation de la méthode du trait HasRoles pour plus de cohérence
        return redirect()->route($user->getDashboardRoute());
    })->name('dashboard');
});

// Routes pour les admins
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->middleware('role:admin')->name('admin.dashboard');
});

// Routes pour les clients
Route::middleware(['auth'])->prefix('client')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->middleware('role:client')->name('client.dashboard');
});

// Routes pour les restaurateurs
Route::middleware(['auth'])->prefix('restaurateur')->group(function () {
    Route::get('/dashboard', [RestaurantDashboardController::class, 'index'])->middleware('role:restaurateur')->name('restaurateur.dashboard');
});

// Routes pour les clients authentifiés
Route::middleware(['auth'])->group(function () {
    Route::get('/restaurants/{restaurant}/menu', [RestaurantController::class, 'showMenu'])->name('restaurants.menu');
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->middleware('role:client')->name('orders.index');
        Route::get('/orders/{order}', 'show')->middleware('role:client')->name('orders.show');
        Route::post('/orders', 'store')->middleware('role:client')->name('orders.store');
        Route::patch('/orders/{order}/cancel', 'cancel')->middleware('role:client')->name('orders.cancel');
    });
});

// Routes pour les restaurateurs authentifiés
Route::middleware(['auth'])->group(function () {
    Route::controller(RestaurantController::class)->group(function () {
        Route::get('/restaurants', 'index')->middleware('role:restaurateur')->name('restaurants.index');
        Route::post('/restaurants', 'store')->middleware('role:restaurateur')->name('restaurants.store');
        Route::get('/restaurants/create', 'create')->middleware('role:restaurateur')->name('restaurants.create');
        Route::get('/restaurants/{restaurant}/edit', 'edit')->middleware('role:restaurateur')->name('restaurants.edit');
        Route::put('/restaurants/{restaurant}', 'update')->middleware('role:restaurateur')->name('restaurants.update');
        Route::delete('/restaurants/{restaurant}', 'destroy')->middleware('role:restaurateur')->name('restaurants.destroy');
    });
    
    Route::resource('restaurants.categories', CategoryController::class)->middleware('role:restaurateur');
    Route::resource('categories.items', ItemController::class)->middleware('role:restaurateur');
    
    Route::controller(OrderController::class)->prefix('restaurants/{restaurant}')->group(function () {
        Route::get('/orders', 'index')->middleware('role:restaurateur')->name('restaurants.orders.index');
        Route::get('/orders/{order}', 'show')->middleware('role:restaurateur')->name('restaurants.orders.show');
        Route::patch('/orders/{order}', 'update')->middleware('role:restaurateur')->name('restaurants.orders.update');
    });
});

// Routes communes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Permet d'accéder au restaurant
    Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
});

require __DIR__.'/auth.php';