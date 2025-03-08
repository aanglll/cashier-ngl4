<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\Stock;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::latest()
            ->with(['user', 'customer'])
            ->paginate(10);
        $customers = Customer::latest()->paginate(10);
        $products = Product::with([
            'category' => function ($query) {
                $query->where('status', 'active');
            },
        ])
            ->latest()
            ->paginate(10);
        $categories = ProductCategory::where('status', 'active')->get();
        $productUnits = ProductUnit::where('status', 'active')->get();
        return view('backend.sales.index', compact('sales', 'customers', 'products', 'categories', 'productUnits'));
    }

    public function create()
    {
        $customers = Customer::latest()->paginate(10);
        $date = now()->format('d/m/Y');

        // Hitung jumlah penjualan hari ini untuk membuat nomor urut
        $salesCountToday = Sale::whereDate('created_at', now()->toDateString())->count() + 1;

        // Format nomor invoice: Tanggal-Hari Ini + Nomor Urut
        $invoiceNumber = $date . '-' . str_pad($salesCountToday, 4, '0', STR_PAD_LEFT);
        $products = Product::with([
            'category' => function ($query) {
                $query->where('status', 'active');
            },
        ])
            ->latest()
            ->paginate(10);
        $categories = ProductCategory::where('status', 'active')->get();
        $productUnits = ProductUnit::where('status', 'active')->get();
        return view('backend.sales.create', compact('customers', 'invoiceNumber', 'products', 'categories', 'productUnits'));
    }

    public function store(Request $request)
{
    $request->merge([
        'discount' => preg_replace('/[^0-9]/', '', $request->discount),
        'ppn' => preg_replace('/[^0-9]/', '', $request->ppn),
        'total_price' => preg_replace('/[^0-9]/', '', $request->total_price),
        'cash_return' => preg_replace('/[^0-9]/', '', $request->cash_return),
    ]);

    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'discount' => 'nullable|numeric',
        'ppn' => 'nullable|numeric',
        'total_price' => 'required|numeric',
        'cash_paid' => 'required|numeric',
        'cash_return' => 'required|numeric',
        'product_name' => 'required|array|min:1',
        'product_name.*' => 'exists:products,product_name',
        'selling_price' => 'required|array|min:1',
        'selling_price.*' => 'numeric',
        'qty' => 'required|array|min:1',
        'qty.*' => 'numeric|min:1',
        'sub_total' => 'required|array|min:1',
        'sub_total.*' => 'numeric',
    ]);

    $sale = Sale::create([
        'user_id' => auth()->id(),
        'customer_id' => $request->customer_id,
        'discount' => $request->discount ?: 0,
        'ppn' => $request->ppn ?: 0,
        'total_price' => $request->total_price,
        'cash_paid' => $request->cash_paid,
        'cash_return' => $request->cash_return,
    ]);

    foreach ($request->product_name as $index => $productName) {
        $product = Product::where('product_name', $productName)->first();

        // Cek apakah stok cukup sebelum mengurangi
        if ($product->stock < $request->qty[$index]) {
            return redirect()->back()->with('error', "{$product->product_name} product stock is insufficient!");
        }

        SalesDetail::create([
            'sales_id' => $sale->id,
            'product_id' => $product->id,
            'selling_price' => $request->selling_price[$index],
            'qty' => $request->qty[$index],
            'sub_total' => $request->sub_total[$index],
        ]);

        // Kurangi stok produk
        $product->stock -= $request->qty[$index];
        $product->save();

        Stock::create([
            'product_id' => $product->id,
            'stock_in' => 0,
            'stock_out' => $request->qty[$index],
            'current_stock' => $product->stock,
            'source' => 'sale',
        ]);
    }

    return redirect()->route('backend.sales.show', $sale->id)->with('success', 'Transaction saved successfully');
}


    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        SalesDetail::where('sales_id', $sale->id)->delete();

        $sale->delete();

        return redirect()->back()->with('success', 'Transaction successfully deleted');
    }

    public function show($id)
    {
        // Mencari penjualan berdasarkan ID dan memuat detail yang terkait
        $sale = Sale::with(['user', 'customer', 'salesDetails.product'])->findOrFail($id);

        return view('backend.sales.show', compact('sale'));
    }
}
