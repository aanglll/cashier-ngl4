<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('view dashboards')) {
            return redirect()->back()->with('error', 'You do not have permission to view the dashboard.');
        }
        $filter = $request->query('filter', 'today');

        $startDate = null;
        $endDate = null;

        switch ($filter) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
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
                $startDate = Carbon::parse($request->query('start_date'));
                $endDate = Carbon::parse($request->query('end_date'));
                break;
        }

        // Query berdasarkan filter tanggal
        $totalSales = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_price');
        $totalPurchases = Purchase::whereBetween('created_at', [$startDate, $endDate])->sum('total_price');

        // Hitung Net Profit
        $totalNet = $totalSales - $totalPurchases;

        // Hitung jumlah transaksi penjualan
        $totalSaleTransaction = Sale::whereBetween('created_at', [$startDate, $endDate])->count();

        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalUsers = User::count() - 1;
        $totalSuppliers = Supplier::count();
        $settings = Setting::first();

        return view('backend.home', compact('totalProducts', 'totalCustomers', 'totalUsers', 'totalSales', 'totalPurchases', 'totalNet', 'totalSaleTransaction', 'totalSuppliers', 'settings'));
    }

    public function product()
    {
        if (!auth()->user()->can('view products')) {
            return redirect()->back()->with('error', 'You do not have permission to view the product.');
        }
        $settings = Setting::first();
        return view('backend.product.index', compact('settings'));
    }

    public function profile($id)
    {
        if ($id != Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $user = User::findOrFail($id);
        $settings = Setting::first();
        return view('backend.profile.index', compact('user', 'settings'));
    }
}
