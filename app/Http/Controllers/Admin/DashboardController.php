<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $totalUsers = User::count();
        $totalRestaurants = Restaurant::count();
        $totalOrders = Order::count();
        $latestUsers = User::latest()->take(5)->get();
        $latestRestaurants = Restaurant::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRestaurants',
            'totalOrders',
            'latestUsers',
            'latestRestaurants'
        ));
    }
}
