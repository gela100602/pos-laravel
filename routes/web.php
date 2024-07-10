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
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PaymentTransactonController;
use App\Http\Controllers\SalesDetailController;
use App\Http\Controllers\CartController;
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
    // Route::post('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.delete_selected');
    Route::resource('/products', ProductController::class);
    Route::patch('products/mark-as-deleted/{id}', [ProductController::class, 'markAsDeleted'])->name('products.markAsDeleted');

    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/delete-selected', [UserController::class, 'deleteSelected'])->name('users.delete_selected');
    Route::resource('/users', UserController::class);

    Route::get('/transaction/payments', [SalesController::class, 'index'])->name('payment_transaction.index');
    Route::get('/transaction/payments/data', [SalesController::class, 'data'])->name('payment_transaction.data'); // current fixing
    Route::get('/transaction/{id}', [SalesController::class, 'show'])->name('payment_transaction.show');
    Route::delete('/transaction/{id}', [SalesController::class, 'destroy'])->name('payment_transaction.destroy');

    Route::get('/transaction/new-transaction', [SalesController::class, 'create'])->name('transaction.new-transaction');
    Route::post('/transaction/save', [SalesController::class, 'store'])->name('transaction.save');
    Route::get('/transaction/complete', [SalesController::class, 'complete'])->name('transaction.complete');
    // Route::get('/transaction/small-receipt', [SalesController::class, 'smallReceipt'])->name('transaction.small_receipt');
    // Route::get('/transaction/large-receipt', [SalesController::class, 'largeReceipt'])->name('transaction.large_receipt');
    
    /* Route::get('/transaction/{id}/data', [SalesDetailController::class, 'data'])->name('transaction.data'); */
    Route::get('/transaction/loadform/{discount?}/{total?}/{received?}', [SalesDetailController::class, 'loadForm'])->name('transaction.load_form');
    Route::resource('/transaction', SalesDetailController::class)->except('create', 'show', 'edit');
    
    Route::put('/transaction/cart/{id}', [CartController::class, 'updateCart'])->name('transaction.update');
    Route::delete('/transaction/cart/{id}', [CartController::class, 'destroyCartItem'])->name('selectedCartProduct.destroy');
    Route::post('/transaction/cart/saveTransaction', [CartController::class, 'saveTransaction'])->name('transactionCart.cartSave');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/transaction/data/{transactionId}', [CartController::class, 'data'])->name('transaction.data');
    Route::post('/Cart/AddToCart', [CartController::class, 'store'])->name('transaction.store');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Redirect root to login if not authenticated
Route::get('/', function () {
    
    return redirect()->route('login');
})->name('index');