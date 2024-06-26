<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

// Automatically define authentication routes
Auth::routes();

Route::group(['middleware' => ['web', 'auth']], function () {
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
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.delete_selected');
    Route::resource('/products', ProductController::class);

    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/delete-selected', [UserController::class, 'deleteSelected'])->name('users.delete_selected');
    Route::resource('/users', UserController::class);

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Redirect root to login if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
})->name('index');