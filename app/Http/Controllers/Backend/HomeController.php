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

class HomeController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalUsers = User::count() - 1;
        $totalSales = Sale::sum('total_price');
        $totalPurchases = Purchase::sum('total_price');
        $totalSuppliers = Supplier::count();

        return view('backend.home', compact('totalProducts', 'totalCustomers', 'totalUsers', 'totalSales', 'totalPurchases', 'totalSuppliers'));
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
