<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard with statistics.
     */
    public function index()
    {
        // Today's statistics
        $todaySales = Transaction::whereDate('trx_date', Carbon::today())->sum('total');
        $todayTransactions = Transaction::whereDate('trx_date', Carbon::today())->count();

        // This month's statistics
        $monthSales = Transaction::whereMonth('trx_date', Carbon::now()->month)
            ->whereYear('trx_date', Carbon::now()->year)
            ->sum('total');
        $monthTransactions = Transaction::whereMonth('trx_date', Carbon::now()->month)
            ->whereYear('trx_date', Carbon::now()->year)
            ->count();

        // Product count
        $activeProducts = Product::where('is_active', true)->count();
        $totalProducts = Product::count();

        // Low stock ingredients (less than 100 units)
        $lowStockIngredients = Ingredient::where('stock', '<', 100)->count();

        // Recent transactions
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'todayTransactions',
            'monthSales',
            'monthTransactions',
            'activeProducts',
            'totalProducts',
            'lowStockIngredients',
            'recentTransactions'
        ));
    }
}
