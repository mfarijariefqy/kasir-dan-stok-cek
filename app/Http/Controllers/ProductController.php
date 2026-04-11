<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ingredients = \App\Models\Ingredient::orderBy('name')->get();
        return view('products.create', compact('ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        if ($request->has('ingredients')) {
            $ingredientData = [];
            foreach ($request->input('ingredients') as $ingredient) {
                // $ingredient contains 'id' and 'qty'
                $ingredientData[$ingredient['id']] = ['qty' => $ingredient['qty']];
            }
            $product->ingredients()->sync($ingredientData);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('ingredients');
        $ingredients = \App\Models\Ingredient::orderBy('name')->get();
        return view('products.edit', compact('product', 'ingredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        if ($request->has('ingredients')) {
            $ingredientData = [];
            foreach ($request->input('ingredients') as $ingredient) {
                $ingredientData[$ingredient['id']] = ['qty' => $ingredient['qty']];
            }
            $product->ingredients()->sync($ingredientData);
        } else {
            $product->ingredients()->detach();
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
