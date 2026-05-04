<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Salesman;
use App\Models\CashFlow;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $data = Cache::remember('dashboard_data', 60, function() {
            // Total Penjualan
            $totalSales = Sale::sum('total');
            $totalTransactions = Sale::count();
            
            // Status Pembayaran
            $paidSales = Sale::where('status', 'paid')->sum('total');
            $unpaidSales = Sale::where('status', 'unpaid')->sum('total');
            
            // Customer & Salesman
            $totalCustomers = Customer::count();
            $activeCustomers = Customer::where('status', 'active')->count();
            $totalSalesmen = Salesman::count();
            $totalTarget = Salesman::sum('target');
            
            // Kas
            $lastCashFlow = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->first();
            $currentBalance = $lastCashFlow ? $lastCashFlow->balance : 0;
            $totalCashIn = CashFlow::where('type', 'in')->sum('amount');
            $totalCashOut = CashFlow::where('type', 'out')->sum('amount');
            
            // Transaksi Terbaru
            $recentTransactions = Sale::with(['customer', 'salesman'])
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();
            
            // Performa Salesman
            $salesmanPerformance = Salesman::with(['sales' => function($query) {
                    $query->where('status', 'paid');
                }])
                ->get()
                ->map(function($salesman) {
                    $achievement = $salesman->sales->sum('total');
                    $percentage = $salesman->target > 0 ? ($achievement / $salesman->target) * 100 : 0;
                    return [
                        'name' => $salesman->name,
                        'target' => $salesman->target,
                        'achievement' => $achievement,
                        'percentage' => min(100, $percentage),
                    ];
                });
            
            // Stok Hampir Habis
            $lowStockProducts = Product::where('stock', '<=', 10)->limit(5)->get();
            
            // Chart Data (Penjualan 7 hari terakhir)
            $chartData = Sale::select(
                    DB::raw('DATE(date) as date'),
                    DB::raw('SUM(total) as total')
                )
                ->where('date', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Chart Data (Arus Kas 7 hari terakhir)
            $cashFlowChart = CashFlow::select(
                    DB::raw('DATE(date) as date'),
                    DB::raw("SUM(CASE WHEN type = 'in' THEN amount ELSE 0 END) as cash_in"),
                    DB::raw("SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END) as cash_out")
                )
                ->where('date', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return compact(
                'totalSales', 'totalTransactions', 'paidSales', 'unpaidSales',
                'totalCustomers', 'activeCustomers', 'totalSalesmen', 'totalTarget',
                'currentBalance', 'totalCashIn', 'totalCashOut', 'recentTransactions',
                'salesmanPerformance', 'lowStockProducts', 'chartData', 'cashFlowChart'
            );
        });

        return view('admin.dashboard', $data);
    }
}