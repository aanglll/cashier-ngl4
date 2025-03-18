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
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PurchasesExport;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('view purchases')) {
            return redirect()->back()->with('error', 'You do not have permission to view the purchase.');
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
        $purchases = Purchase::whereBetween('created_at', [$startDate, $endDate])
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
            'supplier_id' => 'nullable|exists:suppliers,id',
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
        $purchaseDetails = PurchaseDetail::where('purchase_id', $purchase->id)->get();

        foreach ($purchaseDetails as $detail) {
            $product = Product::find($detail->product_id);

            if ($product) {
                $product->stock -= $detail->qty;
                $product->save();
            }
        }

        PurchaseDetail::where('purchase_id', $purchase->id)->delete();
        $purchase->delete();

        return redirect()->back()->with('success', 'Transaction successfully deleted, stock has been updated.');
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

    // public function exportPDF()
    // {
    //     $purchases = Purchase::all();

    //     $pdf = Pdf::loadView('backend.purchases.pdf', compact('purchases'));
    //     return $pdf->stream('purchases.pdf');
    // }

    public function exportPDF(Request $request)
    {
        \Log::info('Export PDF Request:', $request->all());

        $query = Purchase::query();

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

        $purchases = $query->get();

        \Log::info('Filtered Purchase Count:', ['count' => $purchases->count()]); // Debugging log

        $pdf = Pdf::loadView('backend.purchases.pdf', compact('purchases'));
        return $pdf->download('purchases_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->query('filter', 'today');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        return Excel::download(new PurchasesExport($filter, $startDate, $endDate), 'purchases.xlsx');
    }
}
