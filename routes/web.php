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
Route::get('/', function () {
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
Route::middleware(['auth', CheckRole::class.':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Routes pour les clients
Route::middleware(['auth', CheckRole::class.':client'])->group(function () {
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('/restaurants/{restaurant}/menu', [RestaurantController::class, 'showMenu'])->name('restaurants.menu');
    Route::resource('orders', OrderController::class)->only(['store', 'show', 'index']);
});

// Routes pour les restaurateurs
Route::middleware(['auth', CheckRole::class.':restaurateur'])->group(function () {
    Route::get('/restaurateur/dashboard', [RestaurantDashboardController::class, 'index'])->name('restaurateur.dashboard');
    Route::resource('restaurants', RestaurantController::class)->except(['show']);
    Route::resource('restaurants.categories', CategoryController::class);
    Route::resource('categories.items', ItemController::class);
    Route::resource('restaurants.orders', OrderController::class)->only(['index', 'show', 'update']);
});

// Routes communes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour l'accès aux catégories et items (lecture seule)
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);
    Route::resource('items', ItemController::class)->only(['index', 'show']);
    
    // Permet d'accéder au restaurant
    Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
});

require __DIR__.'/auth.php';