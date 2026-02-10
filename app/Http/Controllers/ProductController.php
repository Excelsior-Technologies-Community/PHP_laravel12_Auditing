<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display all products
    public function index()
    {
        $products = Product::all(); // Retrieve all products from database
        return view('products.index', compact('products')); // Return product list view
    }

    // Show create product form
    public function create()
    {
        return view('products.create'); // Return create product view
    }

    // Store new product in database
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required',
            'price' => 'required|integer',
        ]);

        Product::create($request->all()); // Create new product record

        return redirect()->route('products.index'); // Redirect to product list
    }

    // Show edit form for specific product
    public function edit($id)
    {
        $product = Product::findOrFail($id); // Find product by ID or fail
        return view('products.edit', compact('product')); // Return edit view
    }

    // Update specific product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id); // Retrieve product by ID

        $product->update($request->all()); // Update product data

        return redirect()->route('products.index'); // Redirect to product list
    }

    // Delete specific product
    public function destroy($id)
    {
        Product::findOrFail($id)->delete(); // Delete product by ID
        return redirect()->route('products.index'); // Redirect after deletion
    }

    // Display audit history of specific product
    public function audits($id)
    {
        $product = Product::findOrFail($id); // Retrieve product by ID
        $audits = $product->audits()->latest()->get(); // Get latest audit records

        return view('products.audits', compact('audits')); // Return audit history view
    }
}
