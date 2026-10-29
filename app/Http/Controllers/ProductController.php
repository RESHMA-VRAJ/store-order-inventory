<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get(['id', 'name', 'price', 'tax_percentage', 'stock']);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function lowStock(Request $request)
    {
        $validated = $request->validate([
            'threshold' => ['sometimes', 'integer', 'min:0'],
        ]);

        $threshold = $validated['threshold'] ?? 5;

        $products = Product::where('stock', '<', $threshold)->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function catalog(): View
    {
        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'stock']);

        return view('products.index', ['products' => $products]);
    }
}
