<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display daily sales report.
     */
    public function daily(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $transactions = Transaction::with('items.product')
            ->whereDate('trx_date', $date)
            ->get();

        $totalSales = $transactions->sum('total');
        $totalTransactions = $transactions->count();

        return view('reports.daily', compact('transactions', 'totalSales', 'totalTransactions', 'date'));
    }

    /**
     * Display monthly sales report.
     */
    public function monthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Get daily summary
        $dailySummary = Transaction::selectRaw('DATE(trx_date) as date, COUNT(*) as count, SUM(total) as total')
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalSales = $dailySummary->sum('total');
        $totalTransactions = $dailySummary->sum('count');

        // Get top products
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.trx_date', [$startDate, $endDate])
            ->select('products.name', DB::raw('SUM(transaction_items.qty) as total_qty'), DB::raw('SUM(transaction_items.subtotal) as total_sales'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        return view('reports.monthly', compact('dailySummary', 'totalSales', 'totalTransactions', 'month', 'topProducts'));
    }

    /**
     * Display ingredient stock report.
     */
    public function stock()
    {
        $ingredients = Ingredient::with([
            'logs' => function ($query) {
                $query->latest()->limit(5);
            }
        ])->get();

        return view('reports.stock', compact('ingredients'));
    }
}
