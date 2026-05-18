<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\Request;
use App\Traits\CodeGenerator;

class CashFlowController extends Controller
{
    use CodeGenerator;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', CashFlow::class);
        


        $query = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc');

        $role = auth()->user()->role;
        $salesmanId = auth()->user()->salesman_id;

        if ($role === 'sales') {
            $query->whereHas('sale', function($q) use ($salesmanId) {
                $q->where('salesman_id', $salesmanId);
            });
        } elseif ($role === 'supervisor') {
            $subordinateIds = \App\Models\Salesman::where('supervisor_id', $salesmanId)->pluck('id')->toArray();
            $allowedIds = array_merge([$salesmanId], $subordinateIds);
            $query->whereHas('sale', function($q) use ($allowedIds) {
                $q->whereIn('salesman_id', $allowedIds);
            });
        }
        // Manager and Admin see all

        $cashFlows = $query->get();
        return view('cash-flow.index', compact('cashFlows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = auth()->user()->role;
        if (in_array($role, ['sales', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->authorize('create', CashFlow::class);
        $last = CashFlow::orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        $currentBalance = $last ? (float) $last->balance : 0;
        $autoCode = $this->generateDatedCode(CashFlow::class, 'CF', 'code');
        return view('cash-flow.form', compact('currentBalance', 'autoCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = auth()->user()->role;
        if (in_array($role, ['sales', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->authorize('create', CashFlow::class);
        $validated = $request->validate([
            'code' => 'required|unique:cash_flows,code',
            'date' => 'required|date',
            'type' => 'required|in:in,out',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $cf = CashFlow::create([
            ...$validated,
            'balance' => 0,
        ]);

        $cashFlowService = new \App\Services\CashFlowService();
        $cashFlowService->rebalanceFromDate($cf->date);

        return redirect()->route('cash-flow.index')
            ->with('success', 'Transaksi kas/bank berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(CashFlow $cashFlow)
    {
        $this->authorize('view', $cashFlow);
        return view('cash-flow.show', compact('cashFlow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashFlow $cash_flow)
    {
        $role = auth()->user()->role;
        if (in_array($role, ['sales', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->authorize('update', $cash_flow);
        $currentBalance = (float) $cash_flow->balance;
        $cashFlow = $cash_flow;
        return view('cash-flow.form', compact('cashFlow', 'currentBalance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashFlow $cash_flow)
    {
        $role = auth()->user()->role;
        if (in_array($role, ['sales', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->authorize('update', $cash_flow);
        $validated = $request->validate([
            'code' => 'required|unique:cash_flows,code,' . $cash_flow->id,
            'date' => 'required|date',
            'type' => 'required|in:in,out',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $oldDate = $cash_flow->date;

        $cash_flow->update([
            ...$validated,
            'balance' => 0,
        ]);

        $cashFlowService = new \App\Services\CashFlowService();
        $earliestDate = $oldDate < $validated['date'] ? $oldDate : $validated['date'];
        $cashFlowService->rebalanceFromDate($earliestDate);

        return redirect()->route('cash-flow.index')
            ->with('success', 'Transaksi kas/bank berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashFlow $cash_flow)
    {
        $role = auth()->user()->role;
        if (in_array($role, ['sales', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->authorize('delete', $cash_flow);
        $date = $cash_flow->date;
        $cash_flow->delete();

        $cashFlowService = new \App\Services\CashFlowService();
        $cashFlowService->rebalanceFromDate($date);

        return redirect()->route('cash-flow.index')
            ->with('success', 'Transaksi kas/bank berhasil dihapus');
    }
}
