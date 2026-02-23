<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SalesExecutiveController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Admin\SalesOrderItemsController;
use App\Http\Controllers\Admin\SalesExecutiveSalesHistoryController;
use App\Http\Controllers\Admin\LiveLocationTrackingController;
use App\Http\Controllers\Admin\BillingPDFController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('secure-access', [AuthController::class, 'login'])->name('admin.login');
Route::post('secure-access/login-check', [AuthController::class, 'loginCheck'])->name('admin.login.check');
Route::post('secure-access/logout', [AuthController::class, 'logout'])->name('admin.logout');


Route::middleware(['auth:admin', 'decrypt'])->name('admin.')->prefix('secure-access')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.index');
    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::get('profile-view', [AuthController::class, 'profileView'])->name('profile.view');
    Route::post('password-update', [AuthController::class, 'passwordUpdate'])->name('password.update');

    Route::resource('admin', AdminController::class);
    Route::get('admin/status/{id}', [AdminController::class, 'status'])->name('admin.status');
    Route::get('admin/role/{id}', [AdminController::class, 'role'])->name('admin.role');

    Route::resource('sales_executive', SalesExecutiveController::class);
    Route::get('sales_executive/status/{id}', [SalesExecutiveController::class, 'status'])->name('sales_executive.status');

    Route::resource('category', CategoryController::class);
    Route::get('category/status/{id}', [CategoryController::class, 'status'])->name('category.status');

    Route::resource('product', ProductController::class);
    Route::get('product/status/{id}', [ProductController::class, 'status'])->name('product.status');

    Route::resource('store', StoreController::class);
    Route::get('store/status/{id}', [StoreController::class, 'status'])->name('store.status');

    Route::resource('inventory', InventoryController::class);

    Route::resource('salesOrder', SalesOrderController::class);
    Route::get('salesOrder/status/{id}', [SalesOrderController::class, 'status'])->name('salesOrder.status');

    Route::resource('salesOrderItem', SalesOrderItemsController::class);

    Route::resource('salesExe', SalesExecutiveSalesHistoryController::class);

    Route::resource('livelocationtracking', LiveLocationTrackingController::class);

    Route::resource('BillingPDF', BillingPDFController::class);
    Route::get('BillingPDF/status/{id}', [BillingPDFController::class, 'status'])->name('BillingPDF.status');
});
