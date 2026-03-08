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
    public function index()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact('transactions'));
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
