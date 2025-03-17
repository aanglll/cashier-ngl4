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
        if (!auth()->user()->can('view product units')) {
            return redirect()->back()->with('error', 'You do not have permission to view the product unit.');
        }
        $productUnits = ProductUnit::latest()->paginate(10);
        $settings = Setting::first();
        return view('backend.product.units.index', compact('productUnits', 'settings'));
    }

    public function create()
    {
        if (!auth()->user()->can('create product units')) {
            return redirect()->back()->with('error', 'You do not have permission to create the product unit.');
        }
        $settings = Setting::first();
        return view('backend.product.units.create', compact('settings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create product units')) {
            return redirect()->back()->with('error', 'You do not have permission to create the product unit.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        ProductUnit::create($request->all());

        return redirect()->route('backend.product.units.index')->with('success', 'Product unit created successfully.');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit product units')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the product unit.');
        }
        $settings = Setting::first();
        $productUnit = ProductUnit::findOrFail($id);
        return view('backend.product.units.edit', compact('productUnit', 'settings'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit product units')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the product unit.');
        }
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
        if (!auth()->user()->can('delete product units')) {
            return redirect()->back()->with('error', 'You do not have permission to delete the product unit.');
        }
        $productUnit = ProductUnit::findOrFail($id);

        $productUnit->delete();

        return redirect()->route('backend.product.units.index')->with('success', 'Product unit deleted successfully.');
    }
}
