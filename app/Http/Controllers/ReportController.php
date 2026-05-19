<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Salesman;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function closing()
    {
        $allowedIds = $this->getAllowedSalesmanIds();
        
        $querySales = Sale::query();
        if ($allowedIds !== null) {
            $querySales->whereIn('salesman_id', $allowedIds);
        }

        $totalSales = (clone $querySales)->sum('total');
        $paidSales = (clone $querySales)->where('status', 'paid')->sum('total');
        $unpaidSales = (clone $querySales)->whereIn('status', ['unpaid', 'partial'])->sum('total');

        $paidCount = (clone $querySales)->where('status', 'paid')->count();
        $unpaidCount = (clone $querySales)->whereIn('status', ['unpaid', 'partial'])->count();

        // For CashFlow, if not admin/manager, filter by references related to allowed salesmen
        $cashQuery = CashFlow::query();
        if ($allowedIds !== null) {
            $cashQuery->where(function($q) use ($allowedIds) {
                $q->whereHas('sale', function($sq) use ($allowedIds) {
                    $sq->whereIn('salesman_id', $allowedIds);
                })->orWhereNull('reference_id'); // Allow viewing manual entries if necessary, or restrict strictly
            });
        }

        $cashIn = (clone $cashQuery)->where('type', 'in')->sum('amount');
        $cashOut = (clone $cashQuery)->where('type', 'out')->sum('amount');
        $lastCashFlow = (clone $cashQuery)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        $endingBalance = $lastCashFlow ? $lastCashFlow->balance : 0;

        $salesmanQuery = Salesman::query();
        if ($allowedIds !== null) {
            $salesmanQuery->whereIn('id', $allowedIds);
        }
        $allSalesmen = $salesmanQuery->with(['sales' => function ($q) {
            $q->where('status', 'paid');
        }])->get();

        // 1. Separate Managers (Achievement & Target are sum of their subordinate supervisors and sales)
        $managers = $allSalesmen->where('level', 'manager');
        $managerAssessment = $managers->map(function ($s) {
            $supervisorIds = \App\Models\Salesman::where('supervisor_id', $s->id)->pluck('id')->toArray();
            $salesIds = \App\Models\Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            $teamIds = array_merge($supervisorIds, $salesIds);

            $target = (float) \App\Models\Salesman::whereIn('id', $teamIds)->sum('target');
            $achievement = (float) \App\Models\Sale::whereIn('salesman_id', $teamIds)->where('status', 'paid')->sum('total');

            $pct = $target > 0 ? min(100, round(($achievement / $target) * 100)) : 0;
            $grade = $pct >= 100 ? 'A' : ($pct >= 80 ? 'B' : ($pct >= 60 ? 'C' : 'D'));

            return [
                'name' => $s->name,
                'achievement' => $achievement,
                'target' => $target,
                'percentage' => $pct,
                'grade' => $grade,
            ];
        });

        // 2. Separate Other Salesmen (Supervisors and Sales agents)
        $otherSalesmen = $allSalesmen->whereIn('level', ['supervisor', 'sales']);
        $salesmanAssessment = $otherSalesmen->map(function ($s) {
            $achievement = $s->sales->sum('total');
            $target = (float) $s->target;
            $pct = $target > 0 ? min(100, round(($achievement / $target) * 100)) : 0;
            $grade = $pct >= 100 ? 'A' : ($pct >= 80 ? 'B' : ($pct >= 60 ? 'C' : 'D'));
            return [
                'name' => $s->name,
                'level' => $s->level,
                'achievement' => $achievement,
                'target' => $target,
                'percentage' => $pct,
                'grade' => $grade,
            ];
        });

        $unpaidInvoices = (clone $querySales)->with('customer')
            ->whereIn('status', ['unpaid', 'partial'])
            ->orderBy('date', 'desc')
            ->get();

        return view('report.closing', compact(
            'totalSales', 'paidSales', 'unpaidSales', 'paidCount', 'unpaidCount',
            'cashIn', 'cashOut', 'endingBalance', 'managerAssessment', 'salesmanAssessment', 'unpaidInvoices'
        ));
    }

    public function sales(Request $request)
    {
        $allowedIds = $this->getAllowedSalesmanIds();
        $query = $this->buildSalesReportQuery($request, $allowedIds);

        $sales = $query->get();
        $suppliers = Supplier::orderBy('name')->get(['code', 'name', 'company_name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        $customerQuery = Customer::query()->orderBy('name');
        if ($allowedIds !== null) {
            $customerQuery->whereIn('salesman_id', $allowedIds);
        }
        $customers = $customerQuery->get(['id', 'name']);

        $salesmenQuery = Salesman::query()->orderBy('name');
        if ($allowedIds !== null) {
            $salesmenQuery->whereIn('id', $allowedIds);
        }
        $salesmen = $salesmenQuery->get(['id', 'name']);

        return view('report.sales', compact('sales', 'suppliers', 'categories', 'customers', 'salesmen'));
    }

    public function salesExportCsv(Request $request)
    {
        $allowedIds = $this->getAllowedSalesmanIds();
        $sales = $this->buildSalesReportQuery($request, $allowedIds)->get();

        $filename = 'laporan-penjualan.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($sales) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['Invoice', 'Tanggal', 'Customer', 'Salesman', 'Subtotal', 'Diskon', 'Pajak', 'Total', 'Status']);
            foreach ($sales as $s) {
                fputcsv($out, [
                    $s->invoice_number,
                    optional($s->date)->format('Y-m-d'),
                    $s->customer?->name,
                    $s->salesman?->name,
                    (string) ($s->subtotal ?? 0),
                    (string) ($s->discount ?? 0),
                    (string) ($s->tax ?? 0),
                    (string) ($s->total ?? 0),
                    $s->status,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function salesExportPdf(Request $request)
    {
        $allowedIds = $this->getAllowedSalesmanIds();
        $sales = $this->buildSalesReportQuery($request, $allowedIds)->get();
        $pdf = Pdf::loadView('report.sales-pdf', compact('sales'))->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-penjualan.pdf');
    }

    public function cashFlow(Request $request)
    {
        if (in_array(auth()->user()->role, ['sales', 'supervisor'])) {
            abort(403, 'Anda tidak memiliki akses ke laporan Kas / Bank.');
        }

        $allowedIds = $this->getAllowedSalesmanIds();
        $query = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc');
        
        if ($allowedIds !== null) {
            $query->where(function($q) use ($allowedIds) {
                $q->whereHas('sale', function($sq) use ($allowedIds) {
                    $sq->whereIn('salesman_id', $allowedIds);
                })->orWhereNull('reference_id');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->input('end_date'));
        }

        $cashFlows = $query->get();
        return view('report.cash-flow', compact('cashFlows'));
    }

    public function cashFlowExportPdf(Request $request)
    {
        if (in_array(auth()->user()->role, ['sales', 'supervisor'])) {
            abort(403, 'Anda tidak memiliki akses ke laporan Kas / Bank.');
        }

        $allowedIds = $this->getAllowedSalesmanIds();
        $query = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc');
        
        if ($allowedIds !== null) {
            $query->where(function($q) use ($allowedIds) {
                $q->whereHas('sale', function($sq) use ($allowedIds) {
                    $sq->whereIn('salesman_id', $allowedIds);
                })->orWhereNull('reference_id');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->input('end_date'));
        }

        $cashFlows = $query->get();
        $pdf = Pdf::loadView('report.cash-flow-pdf', compact('cashFlows'))->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-kas.pdf');
    }

    public function closingExportPdf()
    {
        // Re-use logic from closing() or call it, but let's re-implement for PDF consistency
        $allowedIds = $this->getAllowedSalesmanIds();
        
        $querySales = Sale::query();
        if ($allowedIds !== null) {
            $querySales->whereIn('salesman_id', $allowedIds);
        }

        $totalSales = (clone $querySales)->sum('total');
        $paidSales = (clone $querySales)->where('status', 'paid')->sum('total');
        $unpaidSales = (clone $querySales)->whereIn('status', ['unpaid', 'partial'])->sum('total');
        $paidCount = (clone $querySales)->where('status', 'paid')->count();
        $unpaidCount = (clone $querySales)->whereIn('status', ['unpaid', 'partial'])->count();

        $cashQuery = CashFlow::query();
        if ($allowedIds !== null) {
            $cashQuery->where(function($q) use ($allowedIds) {
                $q->whereHas('sale', function($sq) use ($allowedIds) {
                    $sq->whereIn('salesman_id', $allowedIds);
                })->orWhereNull('reference_id');
            });
        }
        $cashIn = (clone $cashQuery)->where('type', 'in')->sum('amount');
        $cashOut = (clone $cashQuery)->where('type', 'out')->sum('amount');
        $lastCashFlow = (clone $cashQuery)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        $endingBalance = $lastCashFlow ? $lastCashFlow->balance : 0;

        $salesmanQuery = Salesman::query();
        if ($allowedIds !== null) {
            $salesmanQuery->whereIn('id', $allowedIds);
        }
        $allSalesmen = $salesmanQuery->with(['sales' => function ($q) {
            $q->where('status', 'paid');
        }])->get();

        // 1. Separate Managers (Achievement & Target are sum of their subordinate supervisors and sales)
        $managers = $allSalesmen->where('level', 'manager');
        $managerAssessment = $managers->map(function ($s) {
            $supervisorIds = \App\Models\Salesman::where('supervisor_id', $s->id)->pluck('id')->toArray();
            $salesIds = \App\Models\Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            $teamIds = array_merge($supervisorIds, $salesIds);

            $target = (float) \App\Models\Salesman::whereIn('id', $teamIds)->sum('target');
            $achievement = (float) \App\Models\Sale::whereIn('salesman_id', $teamIds)->where('status', 'paid')->sum('total');

            $pct = $target > 0 ? min(100, round(($achievement / $target) * 100)) : 0;
            $grade = $pct >= 100 ? 'A' : ($pct >= 80 ? 'B' : ($pct >= 60 ? 'C' : 'D'));

            return [
                'name' => $s->name,
                'achievement' => $achievement,
                'target' => $target,
                'percentage' => $pct,
                'grade' => $grade,
            ];
        });

        // 2. Separate Other Salesmen (Supervisors and Sales agents)
        $otherSalesmen = $allSalesmen->whereIn('level', ['supervisor', 'sales']);
        $salesmanAssessment = $otherSalesmen->map(function ($s) {
            $achievement = $s->sales->sum('total');
            $target = (float) $s->target;
            $pct = $target > 0 ? min(100, round(($achievement / $target) * 100)) : 0;
            $grade = $pct >= 100 ? 'A' : ($pct >= 80 ? 'B' : ($pct >= 60 ? 'C' : 'D'));
            return [
                'name' => $s->name,
                'level' => $s->level,
                'achievement' => $achievement,
                'target' => $target,
                'percentage' => $pct,
                'grade' => $grade,
            ];
        });

        $unpaidInvoices = (clone $querySales)->with('customer')->whereIn('status', ['unpaid', 'partial'])->orderBy('date', 'desc')->get();

        $pdf = Pdf::loadView('report.closing-pdf', compact(
            'totalSales', 'paidSales', 'unpaidSales', 'paidCount', 'unpaidCount',
            'cashIn', 'cashOut', 'endingBalance', 'managerAssessment', 'salesmanAssessment', 'unpaidInvoices'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-closing.pdf');
    }

    private function getAllowedSalesmanIds()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return null; // All access
        }

        if ($user->role === 'manager') {
            $supervisorIds = Salesman::where('supervisor_id', $user->salesman_id)->pluck('id')->toArray();
            $salesIds = Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            return array_merge([$user->salesman_id], $supervisorIds, $salesIds);
        }

        if ($user->role === 'supervisor') {
            $subordinateIds = Salesman::where('supervisor_id', $user->salesman_id)->pluck('id')->toArray();
            return array_merge([$user->salesman_id], $subordinateIds);
        }

        if ($user->role === 'sales') {
            return [$user->salesman_id];
        }

        return []; // No access
    }

    private function buildSalesReportQuery(Request $request, ?array $allowedIds)
    {
        $query = \App\Models\SaleHistory::with(['customer', 'salesman', 'sale.items.product.supplier', 'sale.items.product.category'])
            ->orderBy('date', 'desc')->orderBy('id', 'desc');

        if ($allowedIds !== null) {
            $query->whereIn('salesman_id', $allowedIds);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->input('end_date'));
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->integer('customer_id'));
        }

        if ($request->filled('salesman_id')) {
            $salesmanId = $request->integer('salesman_id');
            if ($allowedIds === null || in_array($salesmanId, $allowedIds)) {
                $query->where('salesman_id', $salesmanId);
            }
        }

        if ($request->filled('supplier_code')) {
            $supplierCode = $request->string('supplier_code')->toString();
            $query->whereHas('sale.items.product', function ($q) use ($supplierCode) {
                $q->where('supplier_code', $supplierCode);
            });
        }

        if ($request->filled('category_id')) {
            $categoryId = $request->integer('category_id');
            $query->whereHas('sale.items.product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        return $query;
    }
}
