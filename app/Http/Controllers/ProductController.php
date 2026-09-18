<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display all products.
     */
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show create product form.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show edit product form.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Display audit history for a specific product.
     */
    public function audits($id)
    {
        $product = Product::findOrFail($id);

        $audits = $product->audits()
            ->latest()
            ->get();

        return view('products.audits', compact(
            'product',
            'audits'
        ));
    }
}