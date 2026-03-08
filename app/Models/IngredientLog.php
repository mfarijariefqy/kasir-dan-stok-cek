<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'type',
        'qty',
        'note',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
    ];

    /**
     * Get the ingredient
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
