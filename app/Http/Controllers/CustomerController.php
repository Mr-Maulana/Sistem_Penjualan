<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Salesman;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('salesman')->orderBy('code')->get();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesmen = Salesman::orderBy('name')->get();
        return view('customer.form', compact('salesmen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:customers,code',
            'name' => 'required',
            'address' => 'required',
            'city' => 'nullable|string|max:100',
            'phone' => 'required',
            'group' => 'nullable|string|max:50',
            'salesman_id' => 'nullable|exists:salesmen,id',
            'status' => 'required|in:active,inactive',
        ]);

        Customer::create($validated);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('customer.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $salesmen = Salesman::orderBy('name')->get();
        return view('customer.form', compact('customer', 'salesmen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required',
            'address' => 'required',
            'city' => 'nullable|string|max:100',
            'phone' => 'required',
            'group' => 'nullable|string|max:50',
            'salesman_id' => 'nullable|exists:salesmen,id',
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update($validated);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
