<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cashFlows = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        return view('cash-flow.index', compact('cashFlows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $last = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        $currentBalance = $last ? (float) $last->balance : 0;
        return view('cash-flow.form', compact('currentBalance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:cash_flows,code',
            'date' => 'required|date',
            'type' => 'required|in:in,out',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $last = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        $balance = $last ? (float) $last->balance : 0;
        $balance = $validated['type'] === 'in' ? ($balance + (float) $validated['amount']) : ($balance - (float) $validated['amount']);

        CashFlow::create([
            ...$validated,
            'balance' => $balance,
        ]);

        return redirect()->route('cash-flow.index')
            ->with('success', 'Transaksi kas/bank berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('cash-flow.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashFlow $cash_flow)
    {
        $currentBalance = (float) $cash_flow->balance;
        $cashFlow = $cash_flow;
        return view('cash-flow.form', compact('cashFlow', 'currentBalance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashFlow $cash_flow)
    {
        $validated = $request->validate([
            'code' => 'required|unique:cash_flows,code,' . $cash_flow->id,
            'date' => 'required|date',
            'type' => 'required|in:in,out',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        // Simpel: update transaksi + hitung ulang balance item ini berdasarkan balance sebelumnya (tanpa rebalancing seluruh history).
        // Untuk akurasi penuh, sebaiknya lakukan rebalancing semua record setelah tanggal ini.
        $previous = CashFlow::where('id', '<', $cash_flow->id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        $prevBalance = $previous ? (float) $previous->balance : 0;
        $newBalance = $validated['type'] === 'in'
            ? ($prevBalance + (float) $validated['amount'])
            : ($prevBalance - (float) $validated['amount']);

        $cash_flow->update([
            ...$validated,
            'balance' => $newBalance,
        ]);

        return redirect()->route('cash-flow.index')
            ->with('success', 'Transaksi kas/bank berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashFlow $cash_flow)
    {
        $cash_flow->delete();

        return redirect()->route('cash-flow.index')
            ->with('success', 'Transaksi kas/bank berhasil dihapus');
    }
}
