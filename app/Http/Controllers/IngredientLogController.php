<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredientLogRequest;
use App\Models\Ingredient;
use App\Models\IngredientLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredientLogController extends Controller
{
    /**
     * Display a listing of ingredient logs.
     */
    public function index()
    {
        $logs = IngredientLog::with('ingredient')
            ->latest()
            ->paginate(20);

        return view('ingredients.logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new log (stock adjustment).
     */
    public function create()
    {
        $ingredients = Ingredient::all();
        return view('ingredients.logs.create', compact('ingredients'));
    }

    /**
     * Store a newly created log and update ingredient stock.
     */
    public function store(StoreIngredientLogRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validated = $request->validated();

            // Create log
            IngredientLog::create($validated);

            // Update ingredient stock
            $ingredient = Ingredient::findOrFail($validated['ingredient_id']);

            switch ($validated['type']) {
                case 'IN':
                    $ingredient->stock += $validated['qty'];
                    break;
                case 'OUT':
                    $ingredient->stock -= $validated['qty'];
                    break;
                case 'ADJUST':
                    $ingredient->stock = $validated['qty'];
                    break;
            }

            $ingredient->save();
        });

        return redirect()->route('ingredient-logs.index')
            ->with('success', 'Stok berhasil diperbarui');
    }
}
