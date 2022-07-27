<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TempTransactionController;
use App\Models\TempTransaction;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login/authenticate', [LoginController::class, 'login'])->name('login.authenticate');
});

Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/products/get-products', [ProductController::class, 'getProducts'])->name('products.get-products');
    Route::post('/products/get-product-detail', [ProductController::class, 'getProductDetail'])->name('products.get-product-detail');
    Route::patch('/products/update', [ProductController::class, 'update'])->name('products.update');
    Route::resource('products', ProductController::class)->except(['create', 'edit', 'update', 'destroy']);

    Route::get('cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('cashier/store', [CashierController::class, 'store'])->name('cashier.store');



    Route::get('temp-transaction', [TempTransactionController::class, 'index'])->name('temp-transaction.index');
    Route::post('temp-transaction/store', [TempTransactionController::class, 'store'])->name('temp-transaction.store');
    Route::get('temp-transaction/create', [TempTransactionController::class, 'create'])->name('temp-transaction.create');
    Route::put('temp-transaction/{id}', [TempTransactionController::class, 'update'])->name('temp-transaction.update');
    Route::post('temp-transaction/destroy', [TempTransactionController::class, 'destroy'])->name('temp-transaction.destroy');
});



// Route::get('/', function () {
//     return view('welcome');
// });
