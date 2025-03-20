<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\ProductCategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductUnitController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\RolePermissionController;
use App\Http\Controllers\Backend\SaleController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\StockController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\SettingController;
use App\Models\Product;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/logout', function () {
    return redirect()->route('home');
});

// Auth::routes();

Route::prefix('dashboard')
    ->middleware(['auth', 'active'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/product', [HomeController::class, 'product'])->name('backend.product');

        Route::get('/product/categories', [ProductCategoryController::class, 'index'])->name('backend.product.categories.index');
        Route::get('/product/categories/create', [ProductCategoryController::class, 'create'])->name('backend.product.categories.create');
        Route::post('/product/categories', [ProductCategoryController::class, 'store'])->name('backend.product.categories.store');
        Route::get('/product/categories/{id}/edit', [ProductCategoryController::class, 'edit'])->name('backend.product.categories.edit');
        Route::put('/product/categories/{id}', [ProductCategoryController::class, 'update'])->name('backend.product.categories.update');
        Route::delete('/product/categories/{id}', [ProductCategoryController::class, 'destroy'])->name('backend.product.categories.destroy');

        Route::get('/product/units', [ProductUnitController::class, 'index'])->name('backend.product.units.index');
        Route::get('/product/units/create', [ProductUnitController::class, 'create'])->name('backend.product.units.create');
        Route::post('/product/units', [ProductUnitController::class, 'store'])->name('backend.product.units.store');
        Route::get('/product/units/{id}/edit', [ProductUnitController::class, 'edit'])->name('backend.product.units.edit');
        Route::put('/product/units/{id}', [ProductUnitController::class, 'update'])->name('backend.product.units.update');
        Route::delete('/product/units/{id}', [ProductUnitController::class, 'destroy'])->name('backend.product.units.destroy');

        Route::get('/product/posts', [ProductController::class, 'index'])->name('backend.product.posts.index');
        Route::get('/product/posts/create', [ProductController::class, 'create'])->name('backend.product.posts.create');
        Route::post('/product/posts', [ProductController::class, 'store'])->name('backend.product.posts.store');
        Route::get('/product/posts/{id}/edit', [ProductController::class, 'edit'])->name('backend.product.posts.edit');
        Route::put('/product/posts/{id}', [ProductController::class, 'update'])->name('backend.product.posts.update');
        Route::delete('/product/posts/{id}', [ProductController::class, 'destroy'])->name('backend.product.posts.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('backend.users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('backend.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('backend.users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('backend.users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('backend.users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('backend.users.destroy');

        Route::get('/customer', [CustomerController::class, 'index'])->name('backend.customer.index');
        Route::get('/customer/create', [CustomerController::class, 'create'])->name('backend.customer.create');
        Route::post('/customer', [CustomerController::class, 'store'])->name('backend.customer.store');
        Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('backend.customer.edit');
        Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('backend.customer.update');
        Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('backend.customer.destroy');

        Route::get('/role-permission', [RolePermissionController::class, 'index'])->name('role-permission.index');
        Route::get('/role-permission/create', [RolePermissionController::class, 'create'])->name('role-permission.create');
        Route::post('/role-permission', [RolePermissionController::class, 'store'])->name('role-permission.store');
        Route::get('/role-permission/{id}/edit', [RolePermissionController::class, 'edit'])->name('role-permission.edit');
        Route::put('/role-permission/{id}', [RolePermissionController::class, 'update'])->name('role-permission.update');
        Route::delete('/role-permission/{id}', [RolePermissionController::class, 'destroy'])->name('role-permission.destroy');

        Route::get('/sales', [SaleController::class, 'index'])->name('backend.sales.index');
        Route::get('/sales/create', [SaleController::class, 'create'])->name('backend.sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('backend.sales.store');
        // Route::get('/sales/{id}/edit', [SaleController::class, 'edit'])->name('backend.sales.edit');
        // Route::put('/sales/{id}/update', [SaleController::class, 'update'])->name('backend.sales.update');
        Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('backend.sales.destroy');
        Route::get('/sales/{id}', [SaleController::class, 'show'])->name('backend.sales.show');

        Route::get('/profile/{id}', [HomeController::class, 'profile'])->name('profile.index');

        Route::get('/supplier', [SupplierController::class, 'index'])->name('backend.supplier.index');
        Route::get('/supplier/create', [SupplierController::class, 'create'])->name('backend.supplier.create');
        Route::post('/supplier', [SupplierController::class, 'store'])->name('backend.supplier.store');
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('backend.supplier.edit');
        Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('backend.supplier.update');
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('backend.supplier.destroy');

        Route::get('/purchases', [PurchaseController::class, 'index'])->name('backend.purchases.index');
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('backend.purchases.create');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('backend.purchases.store');
        // Route::get('/purchases/{id}/edit', [PurchaseController::class, 'edit'])->name('backend.purchases.edit');
        // Route::put('/purchases/{id}/update', [PurchaseController::class, 'update'])->name('backend.purchases.update');
        Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->name('backend.purchases.destroy');
        Route::get('/purchases/{id}', [PurchaseController::class, 'show'])->name('backend.purchases.show');

        Route::get('/stocks', [StockController::class, 'index'])->name('backend.stocks.index');
        Route::delete('/stocks/{id}', [StockController::class, 'destroy'])->name('backend.stocks.destroy');

        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    });

Route::get('/sales/export-pdf', [SaleController::class, 'exportPDF'])->name('backend.sales.exportPDF');
Route::get('/sales/export-excel', [SaleController::class, 'exportExcel'])->name('backend.sales.export-excel');

Route::get('/purchases/export-pdf', [PurchaseController::class, 'exportPdf'])->name('backend.purchases.exportPDF');
Route::get('/purchases/excel', [PurchaseController::class, 'exportExcel'])->name('backend.purchases.export-excel');

Route::get('/get-product-by-barcode/{barcode}', function ($barcode) {
    $product = Product::where('barcode', $barcode)->first();

    if ($product) {
        return response()->json([
            'success' => true,
            'product' => [
                'barcode' => $product->barcode,
                'name' => $product->product_name,
                'price' => number_format($product->selling_price, 0, '.', ''),
                'before_discount' => number_format($product->before_discount, 0, '.', ''),
                'discount_product' => $product->discount_product,
                'stock' => $product->stock,
            ],
        ]);
    } else {
        return response()->json(['success' => false]);
    }
});
