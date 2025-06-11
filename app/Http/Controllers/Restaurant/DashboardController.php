<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Auth\Middleware\Authenticate;

class DashboardController extends Controller
{
    #[Authenticate]
    public function index(): View
    {
        $user = auth()->user();
        $restaurants = $user->restaurants()->with(['orders' => function($query) {
            $query->whereDate('created_at', today())->orderBy('created_at', 'desc');
        }])->get();

        return view('restaurant.dashboard', [
            'restaurants' => $restaurants
        ]);
    }
}
