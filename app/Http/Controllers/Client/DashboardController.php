<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $restaurants = Restaurant::with(['categories' => function($query) {
            $query->with(['items' => function($itemQuery) {
                $itemQuery->where('is_active', true);
            }]);
        }])->get();

        return view('client.dashboard', [
            'restaurants' => $restaurants
        ]);
    }
}
