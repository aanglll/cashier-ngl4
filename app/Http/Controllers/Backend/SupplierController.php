<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view suppliers')) {
            return redirect()->back()->with('error', 'You do not have permission to view the supplier.');
        }
        $suppliers = Supplier::latest()->paginate(10);
        $settings = Setting::first();
        return view('backend.supplier.index', compact('suppliers', 'settings'));
    }

    public function create()
    {
        if (!auth()->user()->can('create suppliers')) {
            return redirect()->back()->with('error', 'You do not have permission to create the supplier.');
        }
        $settings = Setting::first();
        return view('backend.supplier.create', compact('settings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create suppliers')) {
            return redirect()->back()->with('error', 'You do not have permission to create the supplier.');
        }
        $request->validate([
            'name' => 'required|string|max:40',
            'address' => 'required|string',
            'phone' => 'required|digits_between:1,15',
        ]);

        Supplier::create($request->all());

        return redirect()->back()->with('success', 'Supplier added successfully.');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit suppliers')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the supplier.');
        }
        $supplier = Supplier::findOrFail($id);
        $settings = Setting::first();
        return view('backend.supplier.edit', compact('supplier', 'settings'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit suppliers')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the supplier.');
        }
        $request->validate([
            'name' => 'required|string|max:40',
            'address' => 'required|string',
            'phone' => 'required|digits_between:1,15',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return redirect()->route('backend.supplier.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete suppliers')) {
            return redirect()->back()->with('error', 'You do not have permission to delete the supplier.');
        }
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('backend.supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}
