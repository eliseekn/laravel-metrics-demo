<?php

namespace App\Http\Controllers;

use Eliseekn\LaravelMetrics\LaravelMetrics;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $totalUsers = LaravelMetrics::getMetrics('users', 'id', LaravelMetrics::YEAR, LaravelMetrics::COUNT);
        $totalProducts = LaravelMetrics::getMetrics('products', 'id', LaravelMetrics::YEAR, LaravelMetrics::COUNT);
        $totalOrders = LaravelMetrics::getMetrics('orders', 'id', LaravelMetrics::YEAR, LaravelMetrics::COUNT);

        $period = $request->query('period', LaravelMetrics::WEEK);

        if (str_contains($period, '~')) $period = explode('~', $period, 2);

        $usersTrends = json_encode(LaravelMetrics::getTrends('users', 'id', $period, LaravelMetrics::COUNT));
        $ordersTrends = json_encode(LaravelMetrics::getTrends('orders', 'id', $period, LaravelMetrics::COUNT));

        return view('dashboard', compact('totalUsers', 'totalProducts', 'totalOrders', 'usersTrends', 'ordersTrends'));
    }
}
