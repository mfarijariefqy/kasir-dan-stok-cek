<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'stock',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
    ];

    /**
     * Get ingredient logs
     */
    public function logs()
    {
        return $this->hasMany(IngredientLog::class);
    }

    /**
     * Get the products that use this ingredient.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('qty')->withTimestamps();
    }
}
