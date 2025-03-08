<?php

namespace App\Http\Controllers\Backend;

use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductUnitController extends Controller
{
    public function index()
    {
        $productUnits = ProductUnit::latest()->paginate(10);

        return view('backend.product.units.index', compact('productUnits'));
    }

    public function create()
    {
        return view('backend.product.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        ProductUnit::create($request->all());

        return redirect()->route('backend.product.units.index')->with('success', 'Product unit created successfully.');
    }

    public function edit($id)
    {
        $productUnit = ProductUnit::findOrFail($id);
        return view('backend.product.units.edit', compact('productUnit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $productUnit = ProductUnit::findOrFail($id);
        $productUnit->update($request->all());

        return redirect()->route('backend.product.units.index')->with('success', 'Product unit updated successfully.');
    }

    public function destroy($id)
{
    $productUnit = ProductUnit::findOrFail($id);

    $productUnit->delete();

    return redirect()->route('backend.product.units.index')->with('success', 'Product unit deleted successfully.');
}

}
