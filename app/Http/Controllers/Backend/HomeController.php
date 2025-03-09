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

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'today');
        $startDate = null;
        $endDate = null;

        switch ($filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'previous_month':
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

        return view('backend.home', compact('totalProducts', 'totalCustomers', 'totalUsers', 'totalSales', 'totalPurchases', 'totalNet', 'totalSaleTransaction', 'totalSuppliers'));
    }

    public function product()
    {
        return view('backend.product.index');
    }

    public function profile($id)
    {
        if ($id != Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $user = User::findOrFail($id);
        return view('backend.profile.index', compact('user'));
    }
}
