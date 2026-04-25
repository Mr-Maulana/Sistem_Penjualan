<?php
// routes/web.php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Master Data
Route::resource('distributor', DistributorController::class);
Route::resource('salesman', SalesmanController::class);
Route::resource('customer', CustomerController::class);
Route::resource('product', ProductController::class);

// Transaksi
Route::resource('sale', SaleController::class);
Route::resource('cash-flow', CashFlowController::class);

// Laporan
Route::get('report/closing', [ReportController::class, 'closing'])->name('report.closing');
Route::get('report/sales', [ReportController::class, 'sales'])->name('report.sales');
Route::get('report/cash-flow', [ReportController::class, 'cashFlow'])->name('report.cash-flow');