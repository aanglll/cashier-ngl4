<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('backend.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('backend.customer.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:40',
            'address' => 'required|string',
            'phone' => 'required|digits_between:1,15',
        ]);

        $customer = new Customer();
        $customer->name = $validatedData['name'];
        $customer->address = $validatedData['address'];
        $customer->phone = $validatedData['phone'];
        $customer->save();

        return redirect()->back()->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('backend.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'address' => 'required|string',
            'phone' => 'required|digits_between:1,15',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('backend.customer.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('backend.customer.index')->with('success', 'Customer deleted successfully.');
    }
}
