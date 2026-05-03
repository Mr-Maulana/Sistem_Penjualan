<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data
    Route::resource('distributor', DistributorController::class);
    Route::resource('salesman', SalesmanController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('product', ProductController::class);
    Route::get('price/lookup', [PriceController::class, 'lookup'])->name('price.lookup');
    Route::resource('price', PriceController::class);

    // Transaksi
    Route::get('sale/{sale}/print', [SaleController::class, 'print'])->name('sale.print');
    Route::resource('sale', SaleController::class);
    Route::resource('cash-flow', CashFlowController::class);

    // Laporan
    Route::get('report/closing', [ReportController::class, 'closing'])->name('report.closing');
    Route::get('report/sales', [ReportController::class, 'sales'])->name('report.sales');
    Route::get('report/sales/export/csv', [ReportController::class, 'salesExportCsv'])->name('report.sales.export.csv');
    Route::get('report/sales/export/pdf', [ReportController::class, 'salesExportPdf'])->name('report.sales.export.pdf');
    Route::get('report/cash-flow', [ReportController::class, 'cashFlow'])->name('report.cash-flow');

    // User Management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('user', UserController::class);
    });
});

require __DIR__.'/auth.php';
