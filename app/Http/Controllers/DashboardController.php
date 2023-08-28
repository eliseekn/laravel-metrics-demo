<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Eliseekn\LaravelMetrics\LaravelMetrics;
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
            'totalUsers' => $this->metrics(User::metrics()->count(), $period),
            'totalProducts' => $this->metrics(Product::metrics()->count(), $period),
            'totalOrders' => $this->metrics(Order::metrics()->count(), $period),
            'usersTrends' => json_encode($this->trends(User::metrics()->count(), $period)),
            'ordersTrends' => json_encode($this->trends(Order::metrics()->count(), $period)),
            'ordersStatusTrends' => json_encode($this->trendsByStatus(Order::metrics()->count(), $period)),
            'productsStatusTrends' => json_encode($this->trendsByStatus(Product::metrics()->count(), $period)),
        ]);
    }

    private function metrics(LaravelMetrics $metrics, string|array $period): mixed
    {
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

    private function trends(LaravelMetrics $metrics, string|array $period): array
    {
        if (is_array($period)) {
            return $metrics->between($period[0], $period[1])->trends();
        }

        return match($period) {
            'day' => $metrics->byDay()->trends(),
            'week' => $metrics->byWeek()->trends(),
            'quater_year' => $metrics->byMonth(4)->trends(),
            'half_year' => $metrics->byMonth(6)->trends(),
            'month' => $metrics->byMonth()->trends(),
        };
    }

    private function trendsByStatus(LaravelMetrics $metrics, string|array $period): array
    {
        if (is_array($period)) {
            return $metrics->labelColumn('status')->between($period[0], $period[1])->trends();
        }

        return match($period) {
            'day' => $metrics->labelColumn('status')->byDay()->trends(),
            'week' => $metrics->labelColumn('status')->byWeek()->trends(),
            'quater_year' => $metrics->labelColumn('status')->byMonth(4)->trends(),
            'half_year' => $metrics->labelColumn('status')->byMonth(6)->trends(),
            'month' => $metrics->labelColumn('status')->byMonth()->trends(),
        };
    }
}
