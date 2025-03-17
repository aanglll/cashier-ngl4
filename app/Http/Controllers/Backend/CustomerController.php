<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view customers')) {
            return redirect()->back()->with('error', 'You do not have permission to view the customer.');
        }
        $customers = Customer::latest()->paginate(10);
        $settings = Setting::first();
        return view('backend.customer.index', compact('customers', 'settings'));
    }

    public function create()
    {
        if (!auth()->user()->can('create customers')) {
            return redirect()->back()->with('error', 'You do not have permission to create the customer.');
        }
        $settings = Setting::first();
        return view('backend.customer.create', compact('settings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create customers')) {
            return redirect()->back()->with('error', 'You do not have permission to create the customer.');
        }
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
        if (!auth()->user()->can('edit customers')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the customer.');
        }
        $customer = Customer::findOrFail($id);
        $settings = Setting::first();
        return view('backend.customer.edit', compact('customer', 'settings'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit customers')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the customer.');
        }
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
        if (!auth()->user()->can('delete customers')) {
            return redirect()->back()->with('error', 'You do not have permission to delete the customer.');
        }
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('backend.customer.index')->with('success', 'Customer deleted successfully.');
    }
}
