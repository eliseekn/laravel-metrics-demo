<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Eliseekn\LaravelMetrics\Enums\Period;
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

        return view('index', [
            'totalUsers' => $this->metrics(User::metrics(), $period),
            'totalUsersToday' => User::metrics()->countByDay(count: 1)->metricsWithVariations(1, Period::DAY->value),
            'totalProducts' => $this->metrics(Product::metrics(), $period),
            'totalProductsToday' => Product::metrics()->countByDay(count: 1)->metricsWithVariations(1, Period::DAY->value),
            'totalOrders' => $this->metrics(Order::metrics(), $period),
            'totalOrdersToday' => Order::metrics()->countByDay(count: 1)->metricsWithVariations(1, Period::DAY->value),
            'usersTrends' => json_encode($this->trends(User::metrics()->fillMissingData(), $period)),
            'ordersTrends' => json_encode($this->trends(Order::metrics()->fillMissingData(), $period)),
            'ordersStatusTrends' => json_encode($this->trendsByStatus(Order::metrics()->fillMissingData(), $period)),
            'productsStatusTrends' => json_encode($this->trendsByStatus(Product::metrics()->fillMissingData(), $period)),
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
            'last_week' => $metrics->countFrom(now()->subWeek()->startOfWeek()->format('Y-m-d'))->metrics(),
            'quater_year' => $metrics->countByMonth(count: 4)->metrics(),
            'half_year' => $metrics->countByMonth(count: 6)->metrics(),
            'month' => $metrics->countByMonth()->metrics(),
            'last_month' => $metrics->countFrom(now()->subMonth()->startOfMonth()->format('Y-m-d'))->metrics(),
            'year' => $metrics->countByYear(count: 5)->metrics(),
            'last_year' => $metrics->countFrom(now()->subYear()->startOfYear()->format('Y-m-d'))->metrics(),
        };
    }

    private function trends(LaravelMetrics $metrics, string|array $period): array
    {
        if (is_array($period)) {
            return $metrics
                ->countBetween($period)
                ->trends();
        }

        return match($period) {
            'day' => $metrics->countByDay()->trends(),
            'last_week' => $metrics->countFrom(now()->subWeek()->startOfWeek()->format('Y-m-d'))->trends(),
            'week' => $metrics->countByWeek()->trends(),
            'quater_year' => $metrics->countByMonth(count: 4)->trends(),
            'half_year' => $metrics->countByMonth(count: 6)->trends(),
            'month' => $metrics->countByMonth()->trends(),
            'last_month' => $metrics->countFrom(now()->subMonth()->startOfMonth()->format('Y-m-d'))->trends(),
            'year' => $metrics->countByYear(count: 5)->trends(),
            'last_year' => $metrics->countFrom(now()->subYear()->startOfYear()->format('Y-m-d'))->trends(),
        };
    }

    private function trendsByStatus(LaravelMetrics $metrics, string|array $period): array
    {
        if (is_array($period)) {
            return $metrics
                ->labelColumn('status')
                ->countBetween($period)
                ->trends(true);
        }

        return match($period) {
            'day' => $metrics->labelColumn('status')->countByDay()->trends(true),
            'last_week' => $metrics->labelColumn('status')->countFrom(now()->subWeek()->startOfWeek()->format('Y-m-d'))->trends(true),
            'week' => $metrics->labelColumn('status')->countByWeek()->trends(true),
            'quater_year' => $metrics->labelColumn('status')->countByMonth(count: 4)->trends(true),
            'half_year' => $metrics->labelColumn('status')->countByMonth(count: 6)->trends(true),
            'month' => $metrics->labelColumn('status')->countByMonth()->trends(true),
            'last_month' => $metrics->labelColumn('status')->countFrom(now()->subMonth()->startOfMonth()->format('Y-m-d'))->trends(true),
            'year' => $metrics->labelColumn('status')->countByYear(count: 5)->trends(true),
            'last_year' => $metrics->labelColumn('status')->countFrom(now()->subYear()->startOfYear()->format('Y-m-d'))->trends(true),
        };
    }
}
