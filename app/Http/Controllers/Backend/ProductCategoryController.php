<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::latest()->paginate(10);

        return view('backend.product.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.product.categories.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        ProductCategory::create([
            'nama' => $request->nama,
            'status' => $request->status,
        ]);

        return redirect()->route('backend.product.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('backend.product.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $category = ProductCategory::findOrFail($id);
        $category->update($request->only(['nama', 'status']));

        return redirect()->route('backend.product.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);

        try {
            $category->delete();

            return redirect()->route('backend.product.categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('backend.product.categories.index')->with('error', 'Failed to delete category. Please try again.');
        }
    }
}
