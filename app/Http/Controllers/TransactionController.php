<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $dateFrom      = $request->input('date_from');
        $dateTo        = $request->input('date_to');
        $paymentMethod = $request->input('payment_method');

        $query = Transaction::with('user')
            ->when($dateFrom, fn($q) => $q->whereDate('trx_date', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('trx_date', '<=', $dateTo))
            ->when($paymentMethod, fn($q) => $q->where('payment_method', $paymentMethod))
            ->latest();

        $grandTotal  = $query->sum('total');
        $totalCount  = $query->count();
        $transactions = $query->paginate(15)->withQueryString();

        return view('transactions.index', compact(
            'transactions', 'grandTotal', 'totalCount',
            'dateFrom', 'dateTo', 'paymentMethod'
        ));
    }

    /**
     * Print all filtered transactions.
     */
    public function printAll(Request $request)
    {
        $dateFrom      = $request->input('date_from');
        $dateTo        = $request->input('date_to');
        $paymentMethod = $request->input('payment_method');

        $transactions = Transaction::with('user', 'items.product')
            ->when($dateFrom, fn($q) => $q->whereDate('trx_date', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('trx_date', '<=', $dateTo))
            ->when($paymentMethod, fn($q) => $q->where('payment_method', $paymentMethod))
            ->latest()
            ->get();

        $grandTotal = $transactions->sum('total');

        return view('transactions.print-all', compact(
            'transactions', 'grandTotal',
            'dateFrom', 'dateTo', 'paymentMethod'
        ));
    }

    /**
     * Show the form for creating a new transaction (Kasir page).
     */
    public function create()
    {
        $products = Product::active()->get();
        return view('transactions.create', compact('products'));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(StoreTransactionRequest $request)
    {
        $transaction = $this->transactionService->createTransaction($request->validated());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil disimpan');
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('items.product', 'user');
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Display printable receipt.
     */
    public function receipt(Transaction $transaction)
    {
        $transaction->load('items.product', 'user');
        return view('transactions.receipt', compact('transaction'));
    }
}
