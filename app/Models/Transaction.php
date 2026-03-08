<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trx_no',
        'trx_date',
        'user_id',
        'payment_method',
        'total',
        'paid',
        'change',
    ];

    protected $casts = [
        'trx_date' => 'date',
        'total' => 'decimal:2',
        'paid' => 'decimal:2',
        'change' => 'decimal:2',
    ];

    /**
     * Get the user who created this transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get transaction items
     */
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
