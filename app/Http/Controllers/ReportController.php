<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Sale;
use App\Models\Salesman;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function closing()
    {
        $totalSales = Sale::sum('total');
        $paidSales = Sale::where('status', 'paid')->sum('total');
        $unpaidSales = Sale::where('status', 'unpaid')->sum('total');

        $paidCount = Sale::where('status', 'paid')->count();
        $unpaidCount = Sale::where('status', 'unpaid')->count();

        $cashIn = CashFlow::where('type', 'in')->sum('amount');
        $cashOut = CashFlow::where('type', 'out')->sum('amount');
        $lastCashFlow = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        $endingBalance = $lastCashFlow ? $lastCashFlow->balance : 0;

        $salesmen = Salesman::with(['sales' => function ($q) {
            $q->where('status', 'paid');
        }])->get();

        $salesmanAssessment = $salesmen->map(function ($s) {
            $achievement = $s->sales->sum('total');
            $target = (float) $s->target;
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

        $unpaidInvoices = Sale::with('customer')
            ->where('status', 'unpaid')
            ->orderBy('date', 'desc')
            ->get();

        return view('report.closing', compact(
            'totalSales',
            'paidSales',
            'unpaidSales',
            'paidCount',
            'unpaidCount',
            'cashIn',
            'cashOut',
            'endingBalance',
            'salesmanAssessment',
            'unpaidInvoices'
        ));
    }

    public function sales()
    {
        $sales = Sale::with(['customer', 'salesman'])->orderBy('date', 'desc')->get();
        return view('report.sales', compact('sales'));
    }

    public function cashFlow()
    {
        $cashFlows = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        return view('report.cash-flow', compact('cashFlows'));
    }
}
