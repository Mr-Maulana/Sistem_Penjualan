<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Sale;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function salesExportCsv()
    {
        $sales = Sale::with(['customer', 'salesman'])->orderBy('date', 'desc')->get();

        $filename = 'laporan-penjualan.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($sales) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM for Excel
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

    public function salesExportPdf()
    {
        $sales = Sale::with(['customer', 'salesman'])->orderBy('date', 'desc')->get();
        $pdf = Pdf::loadView('report.sales-pdf', compact('sales'))->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-penjualan.pdf');
    }

    public function cashFlow()
    {
        $cashFlows = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        return view('report.cash-flow', compact('cashFlows'));
    }

    public function cashFlowExportPdf()
    {
        $cashFlows = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        $pdf = Pdf::loadView('report.cash-flow-pdf', compact('cashFlows'))->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-kas.pdf');
    }

    public function closingExportPdf()
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

        $pdf = Pdf::loadView('report.closing-pdf', compact(
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
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-closing.pdf');
    }
}
