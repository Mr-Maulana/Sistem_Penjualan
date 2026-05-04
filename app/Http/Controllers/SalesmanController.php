<?php

namespace App\Http\Controllers;

use App\Models\Salesman;
use Illuminate\Http\Request;
use App\Traits\CodeGenerator;

class SalesmanController extends Controller
{
    use CodeGenerator;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesmen = Salesman::orderBy('code')->get();
        return view('salesman.index', compact('salesmen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $autoCode = $this->generateCode(Salesman::class, 'SLS');
        return view('salesman.form', compact('autoCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:salesmen,code',
            'name' => 'required',
            'area' => 'required',
            'phone' => 'required',
            'target' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Salesman::create($validated);

        return redirect()->route('salesman.index')
            ->with('success', 'Salesman berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salesman $salesman)
    {
        return view('salesman.show', compact('salesman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salesman $salesman)
    {
        return view('salesman.form', compact('salesman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salesman $salesman)
    {
        $validated = $request->validate([
            'code' => 'required|unique:salesmen,code,' . $salesman->id,
            'name' => 'required',
            'area' => 'required',
            'phone' => 'required',
            'target' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $salesman->update($validated);

        return redirect()->route('salesman.index')
            ->with('success', 'Salesman berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salesman $salesman)
    {
        $salesman->delete();

        return redirect()->route('salesman.index')
            ->with('success', 'Salesman berhasil dihapus');
    }
}
