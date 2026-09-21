<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products with search, filters, sorting and pagination.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('name', 'like', '%' . $search . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sort = $request->get('sort', 'latest');

        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;

            case 'price_low':
                $query->orderBy('price', 'asc');
                break;

            case 'price_high':
                $query->orderBy('price', 'desc');
                break;

            case 'name_az':
                $query->orderBy('name', 'asc');
                break;

            case 'name_za':
                $query->orderBy('name', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $activeProducts = Product::where('status', 'active')->count();

        $inactiveProducts = Product::where('status', 'inactive')->count();

        $totalValue = Product::sum('price');

        $averagePrice = Product::avg('price') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->paginate(5)
            ->withQueryString();

        return view('products.index', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'totalValue',
            'averagePrice'
        ));
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
            ->with('user')
            ->latest()
            ->get();

        return view('products.audits', compact(
            'product',
            'audits'
        ));
    }

    /**
     * Bulk delete products.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer', 'exists:products,id'],
        ]);

        $products = Product::whereIn(
            'id',
            $request->product_ids
        )->get();

        $count = $products->count();

        foreach ($products as $product) {
            $product->delete();
        }

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                $count . ' product(s) deleted successfully.'
            );
    }

    /**
     * Bulk status change.
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'bulk_status' => ['required', 'in:active,inactive'],
        ]);

        $products = Product::whereIn(
            'id',
            $request->product_ids
        )->get();

        $count = 0;

        foreach ($products as $product) {
            $product->update([
                'status' => $request->bulk_status,
            ]);

            $count++;
        }

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                $count . ' product(s) status updated successfully.'
            );
    }

    /**
     * Duplicate product.
     */
    public function duplicate($id)
    {
        $product = Product::findOrFail($id);

        $copy = $product->replicate();

        $copy->name = $product->name . ' Copy';

        $copy->save();

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product duplicated successfully.'
            );
    }
}
