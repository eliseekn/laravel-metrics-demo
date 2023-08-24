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
            'totalUsers' => $this->metrics(User::query(), $period),
            'totalProducts' => $this->metrics(Product::query(), $period),
            'totalOrders' => $this->metrics(Order::query(), $period),
            'usersTrends' => json_encode($this->trends(User::query(), $period)),
            'ordersTrends' => json_encode($this->trends(Order::query(), $period)),
            'ordersStatusTrends' => json_encode($this->trendsByStatus(Order::query(), $period)),
            'productsStatusTrends' => json_encode($this->trendsByStatus(Product::query(), $period)),
        ]);
    }

    private function metrics(Builder $builder, string|array $period): mixed
    {
        $metrics = LaravelMetrics::query($builder)->count();

        if (is_array($period)) {
            return $metrics->between($period[0], $period[1])->metrics();
        }

        return match($period) {
            'day' => $metrics->byDay()->metrics(),
            'week' => $metrics->byWeek()->metrics(),
            'quater_year' => $metrics->byMonth(4)->metrics(),
            'half_year' => $metrics->byMonth(6)->metrics(),
            'month' => $metrics->byMonth()->metrics(),
        };
    }

    private function trends(Builder $builder, string|array $period): array
    {
        $trends = LaravelMetrics::query($builder)->count();

        if (is_array($period)) {
            return $trends->between($period[0], $period[1])->trends();
        }

        return match($period) {
            'day' => $trends->byDay()->trends(),
            'week' => $trends->byWeek()->trends(),
            'quater_year' => $trends->byMonth(4)->trends(),
            'half_year' => $trends->byMonth(6)->trends(),
            'month' => $trends->byMonth()->trends(),
        };
    }

    private function trendsByStatus(Builder $builder, string|array $period): array
    {
        $trends = LaravelMetrics::query($builder)->count();

        if (is_array($period)) {
            return $trends->labelColumn('status')->between($period[0], $period[1])->trends();
        }

        return match($period) {
            'day' => $trends->labelColumn('status')->byDay()->trends(),
            'week' => $trends->labelColumn('status')->byWeek()->trends(),
            'quater_year' => $trends->labelColumn('status')->byMonth(4)->trends(),
            'half_year' => $trends->labelColumn('status')->byMonth(6)->trends(),
            'month' => $trends->labelColumn('status')->byMonth()->trends(),
        };
    }
}
