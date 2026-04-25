<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Salesman;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['customer', 'salesman'])->orderBy('date', 'desc')->get();
        return view('sale.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $salesmen = Salesman::orderBy('name')->get();
        return view('sale.form', compact('customers', 'salesmen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:sales,invoice_number',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'salesman_id' => 'required|exists:salesmen,id',
            'subtotal' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string',
        ]);

        $validated['subtotal'] = $validated['subtotal'] ?? $validated['total'];
        $validated['discount'] = $validated['discount'] ?? 0;

        Sale::create($validated);

        return redirect()->route('sale.index')
            ->with('success', 'Penjualan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('sale.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $customers = Customer::orderBy('name')->get();
        $salesmen = Salesman::orderBy('name')->get();
        return view('sale.form', compact('sale', 'customers', 'salesmen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:sales,invoice_number,' . $sale->id,
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'salesman_id' => 'required|exists:salesmen,id',
            'subtotal' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string',
        ]);

        $validated['subtotal'] = $validated['subtotal'] ?? $validated['total'];
        $validated['discount'] = $validated['discount'] ?? 0;

        $sale->update($validated);

        return redirect()->route('sale.index')
            ->with('success', 'Penjualan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sale.index')
            ->with('success', 'Penjualan berhasil dihapus');
    }
}
