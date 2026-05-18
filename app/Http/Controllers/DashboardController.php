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
        $user = auth()->user();
        $allowedIds = $this->getAllowedSalesmanIds();

        // Total Penjualan
        $querySales = Sale::query();
        if ($allowedIds !== null) {
            $querySales->whereIn('salesman_id', $allowedIds);
        }

        $totalSales = (clone $querySales)->sum('total');
        $totalTransactions = (clone $querySales)->count();
        
        // Status Pembayaran
        $paidSales = (clone $querySales)->where('status', 'paid')->sum('total');
        $unpaidSales = (clone $querySales)->where('status', 'unpaid')->sum('total');
        
        // Customer & Salesman
        $queryCust = Customer::query();
        if ($allowedIds !== null) {
            $queryCust->whereIn('salesman_id', $allowedIds);
        }
        $totalCustomers = (clone $queryCust)->count();
        $activeCustomers = (clone $queryCust)->where('status', 'active')->count();

        $querySalesman = Salesman::query();
        if ($allowedIds !== null) {
            $querySalesman->whereIn('id', $allowedIds);
        }
        $totalSalesmen = (clone $querySalesman)->count();
        $totalTarget = (clone $querySalesman)->sum('target');
        
        // Kas (Filtered by Sale references if not admin/manager)
        $cashQuery = CashFlow::query();
        if ($allowedIds !== null) {
            $cashQuery->where(function($q) use ($allowedIds) {
                $q->whereHas('sale', function($sq) use ($allowedIds) {
                    $sq->whereIn('salesman_id', $allowedIds);
                })->orWhereNull('reference_id');
            });
        }
        $lastCashFlow = (clone $cashQuery)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        $currentBalance = $lastCashFlow ? $lastCashFlow->balance : 0;
        $totalCashIn = (clone $cashQuery)->where('type', 'in')->sum('amount');
        $totalCashOut = (clone $cashQuery)->where('type', 'out')->sum('amount');
        
        // Transaksi Terbaru
        $recentTransactions = (clone $querySales)->with(['customer', 'salesman'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
        
        // Performa Salesman
        $salesmanPerformance = (clone $querySalesman)->with(['sales' => function($query) {
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
        
        // Stok Hampir Habis (Products are usually global)
        $lowStockProducts = Product::where('stock', '<=', 10)->limit(5)->get();
        
        // Chart Data (Penjualan 7 hari terakhir)
        $chartData = (clone $querySales)->select(
                DB::raw('DATE(date) as date'),
                DB::raw('SUM(total) as total')
            )
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Chart Data (Arus Kas 7 hari terakhir)
        $cashFlowChart = (clone $cashQuery)->select(
                DB::raw('DATE(date) as date'),
                DB::raw("SUM(CASE WHEN type = 'in' THEN amount ELSE 0 END) as cash_in"),
                DB::raw("SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END) as cash_out")
            )
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartData = $chartData->map(function ($item) {
            return (object) [
                'date' => \Carbon\Carbon::parse($item->getRawOriginal('date', $item->date))->format('d M'),
                'total' => $item->total
            ];
        });

        $cashFlowChart = $cashFlowChart->map(function ($item) {
            return (object) [
                'date' => \Carbon\Carbon::parse($item->getRawOriginal('date', $item->date))->format('d M'),
                'cash_in' => $item->cash_in,
                'cash_out' => $item->cash_out
            ];
        });

        return view('admin.dashboard', compact(
            'totalSales', 'totalTransactions', 'paidSales', 'unpaidSales',
            'totalCustomers', 'activeCustomers', 'totalSalesmen', 'totalTarget',
            'currentBalance', 'totalCashIn', 'totalCashOut', 'recentTransactions',
            'salesmanPerformance', 'lowStockProducts', 'chartData', 'cashFlowChart'
        ));
    }

    private function getAllowedSalesmanIds()
    {
        $user = auth()->user();
        if ($user->role === 'admin' || $user->role === 'manager') {
            return null;
        }
        if ($user->role === 'supervisor') {
            $subordinateIds = Salesman::where('supervisor_id', $user->salesman_id)->pluck('id')->toArray();
            return array_merge([$user->salesman_id], $subordinateIds);
        }
        if ($user->role === 'sales') {
            return [$user->salesman_id];
        }
        return [];
    }
}