<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\Setting;

class ProductController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view products')) {
            return redirect()->back()->with('error', 'You do not have permission to view the product.');
        }
        $products = Product::with([
            'category' => function ($query) {
                $query->where('status', 'active');
            },
        ])
            ->latest()
            ->paginate(10);
        $categories = ProductCategory::where('status', 'active')->get();
        $productUnits = ProductUnit::where('status', 'active')->get();
        $settings = Setting::first();

        return view('backend.product.posts.index', compact('products', 'categories', 'productUnits', 'settings'));
    }

    public function create()
    {
        if (!auth()->user()->can('create products')) {
            return redirect()->back()->with('error', 'You do not have permission to create the product.');
        }
        $productUnits = ProductUnit::where('status', 'active')->get();
        $categories = ProductCategory::where('status', 'active')->get();
        $settings = Setting::first();
        return view('backend.product.posts.create', compact('categories', 'productUnits', 'settings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create products')) {
            return redirect()->back()->with('error', 'You do not have permission to create the product.');
        }
        $request->validate([
            'product_name' => 'required|string|max:255',
            'id_category' => 'required|exists:product_categories,id',
            'product_units' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:15',
            'description' => 'nullable|string',
        ]);

        Product::create([
            'product_name' => $request->input('product_name'),
            'id_category' => $request->input('id_category'),
            'product_units' => $request->input('product_units'),
            'purchase_price' => $request->input('purchase_price'),
            'selling_price' => $request->input('selling_price'),
            'stock' => $request->input('stock'),
            'barcode' => $request->input('barcode'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('backend.product.posts.index')->with('success', 'Product created successfully!');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit products')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the product.');
        }
        $product = Product::findOrFail($id);
        $categories = ProductCategory::where('status', 'active')->get();
        $productUnits = ProductUnit::where('status', 'active')->get();
        $settings = Setting::first();

        return view('backend.product.posts.edit', compact('product', 'categories', 'productUnits', 'settings'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit products')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the product.');
        }
        $request->validate([
            'product_name' => 'required|string|max:255',
            'id_category' => 'required|exists:product_categories,id',
            'product_units' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:15',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'product_name' => $request->input('product_name'),
            'id_category' => $request->input('id_category'),
            'product_units' => $request->input('product_units'),
            'purchase_price' => $request->input('purchase_price'),
            'selling_price' => $request->input('selling_price'),
            'stock' => $request->input('stock'),
            'barcode' => $request->input('barcode'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('backend.product.posts.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete products')) {
            return redirect()->back()->with('error', 'You do not have permission to delete the product.');
        }
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->route('backend.product.posts.index')->with('success', 'Product deleted successfully!');
    }
}
