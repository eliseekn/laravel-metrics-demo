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
            'totalUsers' => $this->metrics(User::metrics(), $period),
            'totalProducts' => $this->metrics(Product::metrics(), $period),
            'totalOrders' => $this->metrics(Order::metrics(), $period),
            'usersTrends' => json_encode($this->trends(User::metrics(), $period)),
            'ordersTrends' => json_encode($this->trends(Order::metrics(), $period)),
            'ordersStatusTrends' => json_encode($this->trendsByStatus(Order::metrics(), $period)),
            'productsStatusTrends' => json_encode($this->trendsByStatus(Product::metrics(), $period)),
        ]);
    }

    private function metrics(LaravelMetrics $metrics, string|array $period): mixed
    {
        if (is_array($period)) {
            return $metrics
                ->countBetween($period)
                ->metrics();
        }

        return match($period) {
            'day' => $metrics->countByDay()->metrics(),
            'week' => $metrics->countByWeek()->metrics(),
            'quater_year' => $metrics->countByMonth(count: 4)->metrics(),
            'half_year' => $metrics->countByMonth(count: 6)->metrics(),
            'month' => $metrics->countByMonth()->metrics(),
        };
    }

    private function trends(LaravelMetrics $metrics, string|array $period): array
    {
        if (is_array($period)) {
            return $metrics
                ->countBetween($period)
                ->fillEmptyDates()
                ->trends();
        }

        return match($period) {
            'day' => $metrics->countByDay()->trends(),
            'week' => $metrics->countByWeek()->trends(),
            'quater_year' => $metrics->countByMonth(count: 4)->trends(),
            'half_year' => $metrics->countByMonth(count: 6)->trends(),
            'month' => $metrics->countByMonth()->trends(),
        };
    }

    private function trendsByStatus(LaravelMetrics $metrics, string|array $period): array
    {
        if (is_array($period)) {
            return $metrics
                ->labelColumn('status')
                ->countBetween($period)
                ->trends();
        }

        return match($period) {
            'day' => $metrics->labelColumn('status')->countByDay()->trends(),
            'week' => $metrics->labelColumn('status')->countByWeek()->trends(),
            'quater_year' => $metrics->labelColumn('status')->countByMonth(count: 4)->trends(),
            'half_year' => $metrics->labelColumn('status')->countByMonth(count: 6)->trends(),
            'month' => $metrics->labelColumn('status')->countByMonth()->trends(),
        };
    }
}
