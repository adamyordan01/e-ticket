<?php

use App\Models\TempTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TempTransactionController;
use App\Http\Controllers\TransactionController;

// use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login/authenticate', [LoginController::class, 'login'])->name('login.authenticate');
});

Route::group(['middleware' => ['auth']], function () {
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/products/get-products', [ProductController::class, 'getProducts'])->name('products.get-products');
    Route::post('/products/get-product-detail', [ProductController::class, 'getProductDetail'])->name('products.get-product-detail');
    Route::patch('/products/update', [ProductController::class, 'update'])->name('products.update');
    Route::resource('products', ProductController::class)->except(['create', 'edit', 'update', 'destroy']);

    Route::get('cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('cashier/store', [CashierController::class, 'store'])->name('cashier.store');


    Route::get('print/{id}', [PrintController::class, 'print'])->name('print');
    Route::get('print', [PrintController::class, 'index'])->name('print.index');

    Route::get('check', [CheckController::class, 'index'])->name('check.index');
    Route::get('check/{id}', [CheckController::class, 'show'])->name('check.show');

    Route::get('temp-transaction', [TempTransactionController::class, 'index'])->name('temp-transaction.index');
    Route::post('temp-transaction/store', [TempTransactionController::class, 'store'])->name('temp-transaction.store');
    Route::get('temp-transaction/create', [TempTransactionController::class, 'create'])->name('temp-transaction.create');
    Route::put('temp-transaction/{id}', [TempTransactionController::class, 'update'])->name('temp-transaction.update');
    Route::post('temp-transaction/destroy', [TempTransactionController::class, 'destroy'])->name('temp-transaction.destroy');

    Route::get('/dashboard/get-income-by-day', [DashboardController::class, 'getIncomeByDay'])->name('dashboard.get-income-by-day');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/get-role', [RoleController::class, 'getRoles'])->name('role.get-role');
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
    Route::post('role/get-role-detail', [RoleController::class, 'getRoleDetail'])->name('role.get-role-detail');
    Route::patch('role/update', [RoleController::class, 'update'])->name('role.update');
    Route::post('role/destroy', [RoleController::class, 'destroy'])->name('role.destroy');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('user/get-users', [UserController::class, 'getUsers'])->name('user.get-users');

    Route::get('/transaction/get-transaction', [TransactionController::class, 'getTransactions'])->name('transaction.get-transaction');
});



// Route::get('/', function () {
//     return view('welcome');
// });
