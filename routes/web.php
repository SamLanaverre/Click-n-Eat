<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Restaurant\DashboardController as RestaurantDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes pour les clients
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('/restaurants/{restaurant}/menu', [RestaurantController::class, 'showMenu'])->name('restaurants.menu');
    Route::resource('orders', OrderController::class)->only(['store', 'show', 'index']);
});

// Routes pour les restaurateurs
Route::middleware(['auth', 'role:restaurateur'])->group(function () {
    Route::get('/restaurant/dashboard', [RestaurantDashboardController::class, 'index'])->name('restaurant.dashboard');
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