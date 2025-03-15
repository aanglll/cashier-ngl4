<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('product')->latest()->paginate(10);
        $settings = Setting::first();
        return view('backend.stocks.index', compact('stocks', 'settings'));
    }
}
