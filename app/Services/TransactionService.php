<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionService
{
    /**
     * Generate unique transaction number
     */
    public function generateTransactionNumber(): string
    {
        $date = Carbon::now()->format('Ymd');
        $lastTransaction = Transaction::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastTransaction ? (int) substr($lastTransaction->trx_no, -4) + 1 : 1;

        return 'TRX' . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create new transaction with items
     * 
     * @param array $data
     * @return Transaction
     */
    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            // Generate transaction number
            $trxNo = $this->generateTransactionNumber();

            // Create transaction
            $transaction = Transaction::create([
                'trx_no' => $trxNo,
                'trx_date' => $data['trx_date'] ?? Carbon::now()->toDateString(),
                'user_id' => auth()->id(),
                'payment_method' => $data['payment_method'] ?? 'Cash',
                'total' => $data['total'],
                'paid' => $data['paid'],
                'change' => $data['change'],
                'customer_name' => $data['customer_name'] ?? null,
            ]);

            // Create transaction items
            foreach ($data['items'] as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Deduct ingredients stock based on product recipe
                $product = \App\Models\Product::with('ingredients')->find($item['product_id']);
                if ($product && $product->ingredients) {
                    foreach ($product->ingredients as $ingredient) {
                        $deductionQty = $item['qty'] * $ingredient->pivot->qty;

                        // Deduct stock
                        $ingredient->decrement('stock', $deductionQty);

                        // Create ingredient log
                        \App\Models\IngredientLog::create([
                            'ingredient_id' => $ingredient->id,
                            'type' => 'OUT',
                            'qty' => $deductionQty,
                            'note' => 'Penjualan produk: ' . $product->name . ' (TRX: ' . $trxNo . ')',
                        ]);
                    }
                }
            }

            // Load relationships for return
            $transaction->load('items.product', 'user');

            return $transaction;
        });
    }
}
