<?php

use App\Http\Controllers\{
    DashboardController,
    CategoryController,
    DiscountController,
    PaymentMethodController,
    SupplierController,
    CustomerController,
    ProductController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/category/data', [CategoryController::class, 'data'])->name('category.data');
Route::resource('/category', CategoryController::class);

Route::get('/discount/data', [DiscountController::class, 'data'])->name('discount.data');
Route::resource('/discount', DiscountController::class);

Route::get('/payment-method/data', [PaymentMethodController::class, 'data'])->name('payment-method.data');
Route::resource('/payment-method', PaymentMethodController::class);

Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
Route::resource('/supplier', SupplierController::class);

Route::get('/customer/data', [CustomerController::class, 'data'])->name('customer.data');
Route::resource('/customer', CustomerController::class);

Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
Route::post('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.delete_selected');
Route::resource('/products', ProductController::class);
