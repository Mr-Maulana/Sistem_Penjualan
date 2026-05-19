<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Api\SupplierInfoController;
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
    Route::resource('supplier', SupplierController::class);
    Route::resource('salesman', SalesmanController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('area', AreaController::class);
    Route::resource('product', ProductController::class);
    Route::get('/api/supplier/{id}/info', [SupplierInfoController::class, 'getInfo'])->name('api.supplier.info');
    Route::get('/api/salesmen-by-city', [CustomerController::class, 'salesmenByCity'])->name('api.salesmen-by-city');
    Route::get('price/lookup', [PriceController::class, 'lookup'])->name('price.lookup');
    Route::resource('price', PriceController::class);

    // Transaksi
    Route::get('sale/{sale}/print', [SaleController::class, 'print'])->name('sale.print');
    Route::resource('sale', SaleController::class);
    Route::resource('cash-flow', CashFlowController::class);

    // Team & Transfer Routes
    Route::get('team', [TeamController::class, 'index'])->name('team.index');
    Route::post('team/transfer', [TeamController::class, 'requestTransfer'])->name('team.transfer');
    Route::post('team/force-transfer', [TeamController::class, 'forceTransfer'])->name('team.force-transfer');
    Route::get('team/approvals', [TeamController::class, 'approvals'])->name('team.approvals');
    Route::post('team/approvals/{transfer}', [TeamController::class, 'processTransfer'])->name('team.process');

    // Laporan
    Route::get('report/closing', [ReportController::class, 'closing'])->name('report.closing');
    Route::get('report/closing/export/pdf', [ReportController::class, 'closingExportPdf'])->name('report.closing.export.pdf');
    Route::get('report/sales', [ReportController::class, 'sales'])->name('report.sales');
    Route::get('report/sales/export/csv', [ReportController::class, 'salesExportCsv'])->name('report.sales.export.csv');
    Route::get('report/sales/export/pdf', [ReportController::class, 'salesExportPdf'])->name('report.sales.export.pdf');
    Route::get('report/cash-flow', [ReportController::class, 'cashFlow'])->name('report.cash-flow');
    Route::get('report/cash-flow/export/pdf', [ReportController::class, 'cashFlowExportPdf'])->name('report.cash-flow.export.pdf');

    Route::post('admin/settings/toggle-dark', [\App\Http\Controllers\SystemAdminController::class, 'toggleDarkMode'])->name('admin.settings.toggle-dark');

    // User Management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::post('user/store-salesman', [UserController::class, 'storeSalesmanAccount'])->name('user.store-salesman');
        Route::resource('user', UserController::class);
        
        // System Administration & Overview
        Route::get('admin/settings', [\App\Http\Controllers\SystemAdminController::class, 'settings'])->name('admin.settings');
        Route::post('admin/settings', [\App\Http\Controllers\SystemAdminController::class, 'saveSettings'])->name('admin.settings.save');
        Route::get('admin/records', [\App\Http\Controllers\SystemAdminController::class, 'records'])->name('admin.records');
        Route::get('admin/activity', [\App\Http\Controllers\SystemAdminController::class, 'activity'])->name('admin.activity');
        Route::get('admin/health', [\App\Http\Controllers\SystemAdminController::class, 'health'])->name('admin.health');
    });
});

require __DIR__.'/auth.php';
