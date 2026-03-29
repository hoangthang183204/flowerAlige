<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $revenueToday = Order::whereDate('created_at', $today)->sum('total_amount');

        $ordersThisMonth = Order::whereBetween('created_at', [$startOfMonth, Carbon::now()])->count();
        $revenueThisMonth = Order::whereBetween('created_at', [$startOfMonth, Carbon::now()])->sum('total_amount');

        $ordersByDay = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $ordersByDay->map(fn ($r) => Carbon::parse($r->date)->format('d/m'))->toArray();
        $chartRevenue = $ordersByDay->pluck('total_revenue')->map(fn ($v) => (float) $v)->toArray();

        // Doanh thu theo tuần (12 tuần gần nhất) - MySQL YEARWEEK = year*100+week
        $weeks = collect();
        for ($i = 11; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek();
            $weeks->push((int) $start->format('o') * 100 + (int) $start->format('W'));
        }
        $revenueByWeek = Order::select(
                DB::raw('YEARWEEK(created_at) as year_week'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->where('created_at', '>=', Carbon::now()->subWeeks(11)->startOfWeek())
            ->groupBy(DB::raw('YEARWEEK(created_at)'))
            ->get()
            ->keyBy('year_week')
            ->map(fn ($r) => (float) $r->total_revenue);
        $chartLabelsWeek = $weeks->map(function ($yw) {
            $y = (int) floor($yw / 100);
            $w = $yw % 100;
            return 'Tuần ' . Carbon::now()->setISODate($y, $w)->format('d/m');
        })->toArray();
        $chartRevenueWeek = $weeks->map(fn ($yw) => $revenueByWeek->get($yw, 0.0))->toArray();

        // Doanh thu theo tháng (12 tháng gần nhất)
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('Y-m'));
        }
        $startOfMonthRange = Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d H:i:s');
        $revenueByMonth = Order::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->whereRaw('created_at >= ?', [$startOfMonthRange])
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get()
            ->keyBy('ym')
            ->map(fn ($r) => (float) $r->total_revenue);
        $chartLabelsMonth = $months->map(fn ($ym) => Carbon::createFromFormat('Y-m', $ym)->format('m/Y'))->toArray();
        $chartRevenueMonth = $months->map(fn ($ym) => $revenueByMonth->get($ym, 0.0))->toArray();

        $topProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->limit(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'ordersToday',
            'revenueToday',
            'ordersThisMonth',
            'revenueThisMonth',
            'ordersByDay',
            'chartLabels',
            'chartRevenue',
            'chartLabelsWeek',
            'chartRevenueWeek',
            'chartLabelsMonth',
            'chartRevenueMonth',
            'topProducts',
            'lowStockProducts'
        ));
    }
}

