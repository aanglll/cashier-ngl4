<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('backend.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('backend.supplier.create');
    }

    public function store(Request $request)
    {
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
        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
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
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('backend.supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}
