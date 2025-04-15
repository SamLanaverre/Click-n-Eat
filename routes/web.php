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

// Route de base - redirige vers login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route pour le dashboard après connexion
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isRestaurateur()) {
            return redirect()->route('restaurateur.dashboard');
        } else {
            return redirect()->route('client.dashboard');
        }
    })->name('dashboard');
});

// Routes pour les admins
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Routes pour les clients
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('/restaurants/{restaurant}/menu', [RestaurantController::class, 'showMenu'])->name('restaurants.menu');
    Route::resource('orders', OrderController::class)->only(['store', 'show', 'index']);
});

// Routes pour les restaurateurs
Route::middleware(['auth', 'role:restaurateur'])->group(function () {
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
});

require __DIR__.'/auth.php';