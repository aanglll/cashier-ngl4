<?php

namespace App\Http\Controllers\Backend;

use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class ProductUnitController extends Controller
{
    public function index()
    {
        $productUnits = ProductUnit::latest()->paginate(10);
        $settings = Setting::first();
        return view('backend.product.units.index', compact('productUnits', 'settings'));
    }

    public function create()
    {
        $settings = Setting::first();
        return view('backend.product.units.create', compact('settings'));
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
        $settings = Setting::first();
        $productUnit = ProductUnit::findOrFail($id);
        return view('backend.product.units.edit', compact('productUnit', 'settings'));
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
