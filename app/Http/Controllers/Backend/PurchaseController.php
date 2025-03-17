<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\Setting;
use App\Models\Stock;

class PurchaseController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view purchases')) {
            return redirect()->back()->with('error', 'You do not have permission to view the purchase.');
        }
        $purchases = Purchase::latest()
            ->with(['user', 'supplier'])
            ->paginate(10);
        $suppliers = Supplier::latest()->paginate(10);
        $products = Product::with(['category' => fn($q) => $q->where('status', 'active')])
            ->latest()
            ->paginate(10);
        $categories = ProductCategory::where('status', 'active')->get();
        $productUnits = ProductUnit::where('status', 'active')->get();
        $settings = Setting::first();

        return view('backend.purchases.index', compact('purchases', 'suppliers', 'products', 'categories', 'productUnits', 'settings'));
    }

    public function create()
    {
        if (!auth()->user()->can('create purchases')) {
            return redirect()->back()->with('error', 'You do not have permission to create the purchase.');
        }
        $suppliers = Supplier::latest()->paginate(10);
        $date = now()->format('d/m/Y');
        $purchaseCountToday = Purchase::whereDate('created_at', now()->toDateString())->count() + 1;
        $invoiceNumber = $date . '-' . str_pad($purchaseCountToday, 4, '0', STR_PAD_LEFT);
        $products = Product::with(['category' => fn($q) => $q->where('status', 'active')])
            ->latest()
            ->paginate(10);
        $categories = ProductCategory::where('status', 'active')->get();
        $productUnits = ProductUnit::where('status', 'active')->get();
        $settings = Setting::first();

        return view('backend.purchases.create', compact('suppliers', 'invoiceNumber', 'products', 'categories', 'productUnits', 'settings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create purchases')) {
            return redirect()->back()->with('error', 'You do not have permission to create the purchase.');
        }
        $request->merge([
            'discount' => preg_replace('/[^0-9]/', '', $request->discount),
            'ppn' => preg_replace('/[^0-9]/', '', $request->ppn),
            'total_price' => preg_replace('/[^0-9]/', '', $request->total_price),
            'cash_return' => preg_replace('/[^0-9]/', '', $request->cash_return),
        ]);

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'discount' => 'nullable|numeric',
            'ppn' => 'nullable|numeric',
            'total_price' => 'required|numeric',
            'cash_paid' => 'required|numeric',
            'cash_return' => 'required|numeric',
            'product_name' => 'required|array|min:1',
            'product_name.*' => 'exists:products,product_name',
            'purchase_price' => 'required|array|min:1',
            'purchase_price.*' => 'numeric',
            'qty' => 'required|array|min:1',
            'qty.*' => 'numeric|min:1',
            'sub_total' => 'required|array|min:1',
            'sub_total.*' => 'numeric',
        ]);

        $purchase = Purchase::create([
            'user_id' => auth()->id(),
            'supplier_id' => $request->supplier_id,
            'discount' => $request->discount ?: 0,
            'ppn' => $request->ppn ?: 0,
            'total_price' => $request->total_price,
            'cash_paid' => $request->cash_paid,
            'cash_return' => $request->cash_return,
        ]);

        foreach ($request->product_name as $index => $productName) {
            $product = Product::where('product_name', $productName)->first();

            PurchaseDetail::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product->id,
                'purchase_price' => $request->purchase_price[$index],
                'qty' => $request->qty[$index],
                'sub_total' => $request->sub_total[$index],
            ]);

            // Update stok produk
            $product->stock += $request->qty[$index];
            $product->save();

            Stock::create([
                'product_id' => $product->id,
                'stock_in' => $request->qty[$index],
                'stock_out' => 0,
                'current_stock' => $product->stock,
                'source' => 'purchase',
            ]);
        }

        return redirect()->route('backend.purchases.show', $purchase->id)->with('success', 'Transaction saved successfully');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete purchases')) {
            return redirect()->back()->with('error', 'You do not have permission to delete the purchase.');
        }
        $purchase = Purchase::findOrFail($id);

        PurchaseDetail::where('purchase_id', $purchase->id)->delete();

        $purchase->delete();

        return redirect()->back()->with('success', 'Transaction successfully deleted');
    }

    public function show($id)
    {
        if (!auth()->user()->can('create purchases')) {
            return redirect()->back()->with('error', 'You do not have permission to create the purchase.');
        }
        $purchase = Purchase::with(['user', 'supplier', 'purchaseDetails.product'])->findOrFail($id);
        $settings = Setting::first();
        return view('backend.purchases.show', compact('purchase', 'settings'));
    }
}
