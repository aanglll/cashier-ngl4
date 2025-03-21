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
use App\Models\Setting;
use App\Models\Stock;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('view sales')) {
            return redirect()->back()->with('error', 'You do not have permission to view the sale.');
        }
        $filter = $request->query('filter', 'today');

        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        switch ($filter) {
            case 'yesterday':
                $startDate = Carbon::now()->subDay()->startOfDay();
                $endDate = Carbon::now()->subDay()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfWeek();
                $endDate = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_month_last_year':
                $startDate = Carbon::now()->subYear()->startOfMonth();
                $endDate = Carbon::now()->subYear()->endOfMonth();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear();
                $endDate = Carbon::now()->subYear()->endOfYear();
                break;
            case 'time_span':
                if ($request->has(['start_date', 'end_date'])) {
                    $startDate = Carbon::parse($request->query('start_date'));
                    $endDate = Carbon::parse($request->query('end_date'));
                }
                break;
        }

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'customer'])
            ->latest()
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
        $settings = Setting::first();

        return view('backend.sales.index', compact('sales', 'customers', 'products', 'categories', 'productUnits', 'filter', 'startDate', 'endDate', 'settings'));
    }

    public function create()
    {
        if (!auth()->user()->can('create sales')) {
            return redirect()->back()->with('error', 'You do not have permission to create the sale.');
        }
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
        // ->where('stock', '>', 0) // Hanya produk dengan stok lebih dari 0
        ->latest()
        ->paginate(10);
        $categories = ProductCategory::where('status', 'active')->get();
        $productUnits = ProductUnit::where('status', 'active')->get();
        $settings = Setting::first();

        return view('backend.sales.create', compact('customers', 'invoiceNumber', 'products', 'categories', 'productUnits', 'settings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create sales')) {
            return redirect()->back()->with('error', 'You do not have permission to create the sale.');
        }
        $request->merge([
            'discount' => preg_replace('/[^0-9]/', '', $request->discount),
            'ppn' => preg_replace('/[^0-9]/', '', $request->ppn),
            'total_price' => preg_replace('/[^0-9]/', '', $request->total_price),
            'cash_return' => preg_replace('/[^0-9]/', '', $request->cash_return),
        ]);

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'discount' => 'nullable|numeric',
            'ppn' => 'nullable|numeric',
            'total_price' => 'required|numeric',
            'cash_paid' => 'required|numeric',
            'cash_return' => 'required|numeric',
            'product_name' => 'required|array|min:1',
            'product_name.*' => 'exists:products,product_name',
            'before_discount' => 'required|array|min:0',
            'before_discount.*' => 'numeric',
            'discount_product' => 'required|array|min:0',
            'discount_product.*' => 'numeric|min:0',
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
                return redirect()
                    ->back()
                    ->with('error', "{$product->product_name} product stock is insufficient!");
            }

            SalesDetail::create([
                'sales_id' => $sale->id,
                'product_id' => $product->id,
                'selling_price' => $request->selling_price[$index],
                'before_discount' => $request->before_discount[$index],
                'discount_product' => $request->discount_product[$index],
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
        if (!auth()->user()->can('delete sales')) {
            return redirect()->back()->with('error', 'You do not have permission to delete the sale.');
        }
        $sale = Sale::findOrFail($id);

        $salesDetails = SalesDetail::where('sales_id', $sale->id)->get();

        foreach ($salesDetails as $detail) {
            $product = Product::find($detail->product_id);

            if ($product) {
                $product->stock += $detail->qty;
                $product->save();
            }
        }

        SalesDetail::where('sales_id', $sale->id)->delete();

        $sale->delete();

        return redirect()->back()->with('success', 'Transaction successfully deleted, stock has been updated.');
    }

    public function show($id)
    {
        if (!auth()->user()->can('create sales')) {
            return redirect()->back()->with('error', 'You do not have permission to create the sale.');
        }
        // Mencari penjualan berdasarkan ID dan memuat detail yang terkait
        $sale = Sale::with(['user', 'customer', 'salesDetails.product'])->findOrFail($id);
        $settings = Setting::first();

        return view('backend.sales.show', compact('sale', 'settings'));
    }

    public function exportPDF(Request $request)
    {
        \Log::info('Export PDF Request:', $request->all());

        $query = Sale::query();

        // Jika tidak ada filter, set default ke "today"
        $filter = $request->filter ?? 'today';

        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', now());
                break;
            case 'yesterday':
                $query->whereDate('created_at', now()->subDay());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', now()->year);
                break;
            case 'last_year':
                $query->whereYear('created_at', now()->subYear()->year);
                break;
            case 'time_span':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
                break;
        }

        $sales = $query->get();

        \Log::info('Filtered Sales Count:', ['count' => $sales->count()]); // Debugging log

        $pdf = Pdf::loadView('backend.sales.pdf', compact('sales'));
        return $pdf->download('sales_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->query('filter', 'today');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        return Excel::download(new SalesExport($filter, $startDate, $endDate), 'sales.xlsx');
    }
}
