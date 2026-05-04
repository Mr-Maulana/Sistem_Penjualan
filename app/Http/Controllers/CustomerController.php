<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Salesman;
use Illuminate\Http\Request;
use App\Traits\CodeGenerator;

class CustomerController extends Controller
{
    use CodeGenerator;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::with('salesman');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        if ($request->filled('salesman_id')) {
            $query->where('salesman_id', $request->salesman_id);
        }

        $customers = $query->orderBy('code')->get();
        $salesmen = Salesman::orderBy('name')->get();
        $groups = Customer::whereNotNull('group')->distinct()->pluck('group');

        return view('customer.index', compact('customers', 'salesmen', 'groups'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesmen = Salesman::orderBy('name')->get();
        $autoCode = $this->generateCode(Customer::class, 'CST');
        return view('customer.form', compact('salesmen', 'autoCode'));
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
    public function show(Customer $customer)
    {
        return view('customer.show', compact('customer'));
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
