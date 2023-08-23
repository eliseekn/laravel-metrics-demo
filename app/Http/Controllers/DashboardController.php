<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Eliseekn\LaravelMetrics\LaravelMetrics;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $period = $request->query('period', 'day');

        if (str_contains($period, '~')) {
            $period = explode('~', $period, 2);
        }

        return view('dashboard', [
            'totalUsers' => LaravelMetrics::query(User::query())->count()->byYear()->metrics(),
            'totalProducts' => LaravelMetrics::query(Product::query())->count()->byYear()->metrics(),
            'totalOrders' => LaravelMetrics::query(Order::query())->count()->byYear()->metrics(),
            'usersTrends' => json_encode($this->trends(User::query(), $period)),
            'ordersTrends' => json_encode($this->trends(Order::query(), $period))
        ]);
    }

    private function trends(Builder $builder, string|array $period): array
    {
        if (is_array($period)) {
            return LaravelMetrics::query($builder)->count()->between($period[0], $period[1])->trends();
        }

        return match($period) {
            'day' => LaravelMetrics::query($builder)->count()->byDay()->trends(),
            'week' => LaravelMetrics::query($builder)->count()->byWeek()->trends(),
            'quater_year' => LaravelMetrics::query($builder)->count()->byMonth(4)->trends(),
            'half_year' => LaravelMetrics::query($builder)->count()->byMonth(6)->trends(),
            'month' => LaravelMetrics::query($builder)->count()->byMonth()->trends(),
        };
    }
}
