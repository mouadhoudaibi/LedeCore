<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Get order statistics for the chart.
     */
    public function statistics(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])->get();

        $statistics = [
            'pending' => $orders->where('status', 'pending')->count(),
            'validated' => $orders->where('status', 'validated')->count(),
            'refused' => $orders->where('status', 'refused')->count(),
            'delivered' => $orders->where('status', 'delivered')->count(),
        ];

        return response()->json($statistics);
    }

    /**
     * Get revenue statistics (only validated orders).
     */
    public function revenue(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get daily revenue from validated orders only (excluding delivery fees)
        $revenueData = Order::where('status', 'validated')
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format data for Chart.js
        $labels = [];
        $revenues = [];
        $totalRevenue = 0;

        foreach ($revenueData as $item) {
            $labels[] = \Carbon\Carbon::parse($item->date)->format('d/m/Y');
            $revenues[] = (float) $item->revenue;
            $totalRevenue += (float) $item->revenue;
        }

        return response()->json([
            'labels' => $labels,
            'revenues' => $revenues,
            'total' => $totalRevenue,
        ]);
    }
}

