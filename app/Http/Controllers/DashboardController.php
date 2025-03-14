<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Finance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $activeOrders = Order::where('status', 'active')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalRevenue = Finance::sum('daily_result');
        $currentBalance = Finance::orderBy('date', 'desc')->first()->balance ?? 0;

        $recentOrders = Order::with('client', 'bikes')
            ->orderBy('start_time', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalOrders',
            'activeOrders',
            'completedOrders',
            'totalRevenue',
            'currentBalance',
            'recentOrders'
        ));
    }
}