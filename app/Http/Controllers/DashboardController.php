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
        $cacheVersion = Cache::get('dashboard_cache_version', 1);
        $cacheKey = 'dashboard_data_user_' . $user->id . '_v' . $cacheVersion;

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user) {
            $allowedIds = $user->getAllowedSalesmanIds();

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
            
            // Performa Salesman (Optimized with withSum to prevent N+1 queries and memory overhead)
            $salesmanPerformance = (clone $querySalesman)
                ->withSum(['sales as achievement' => function ($query) {
                    $query->where('status', 'paid');
                }], 'total')
                ->get()
                ->map(function ($salesman) {
                    $achievement = (float) ($salesman->achievement ?? 0);
                    $percentage = $salesman->target > 0 ? ($achievement / $salesman->target) * 100 : 0;
                    return [
                        'name' => $salesman->name,
                        'target' => $salesman->target,
                        'achievement' => $achievement,
                        'percentage' => min(100, $percentage),
                    ];
                });
            
            // Stok Hampir Habis (Scoped if manager, supervisor, or sales)
            $productQuery = Product::query()->where('stock', '<=', 10);
            if (in_array($user->role, ['manager', 'supervisor', 'sales']) && $user->salesman_id) {
                $province = null;
                if ($user->role === 'manager') {
                    $salesman = Salesman::find($user->salesman_id);
                    $province = $salesman ? $salesman->area : null;
                } elseif ($user->role === 'supervisor') {
                    $salesman = Salesman::find($user->salesman_id);
                    $manager = $salesman ? Salesman::find($salesman->supervisor_id) : null;
                    $province = $manager ? $manager->area : null;
                } else { // sales
                    $salesman = Salesman::find($user->salesman_id);
                    $supervisor = $salesman ? Salesman::find($salesman->supervisor_id) : null;
                    $manager = $supervisor ? Salesman::find($supervisor->supervisor_id) : null;
                    $province = $manager ? $manager->area : null;
                }

                if ($province) {
                    $cities = \App\Models\Area::where('province', $province)->pluck('city')->unique()->filter()->values()->toArray();
                    $supplierCodes = \App\Models\Supplier::whereIn('city', $cities)->pluck('code')->toArray();
                    $productQuery->whereIn('supplier_code', $supplierCodes);
                }
            }
            $lowStockProducts = $productQuery->limit(5)->get();
            
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

            return compact(
                'totalSales', 'totalTransactions', 'paidSales', 'unpaidSales',
                'totalCustomers', 'activeCustomers', 'totalSalesmen', 'totalTarget',
                'currentBalance', 'totalCashIn', 'totalCashOut', 'recentTransactions',
                'salesmanPerformance', 'lowStockProducts', 'chartData', 'cashFlowChart'
            );
        });

        return view('admin.dashboard', $data);
    }

    /**
     * Invalidate the dashboard cache for all users.
     */
    public static function clearCache()
    {
        try {
            Cache::increment('dashboard_cache_version');
        } catch (\Throwable $e) {
            Cache::forever('dashboard_cache_version', 2);
        }
    }
}