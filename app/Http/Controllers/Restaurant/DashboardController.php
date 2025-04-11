<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $restaurants = $user->restaurants()->with(['categories.items', 'orders' => function($query) {
            $query->whereDate('created_at', today())->orderBy('created_at', 'desc');
        }])->get();

        return view('restaurant.dashboard', [
            'restaurants' => $restaurants
        ]);
    }
}
