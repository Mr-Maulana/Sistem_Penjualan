<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Traits\CodeGenerator;

class SupplierController extends Controller
{
    use CodeGenerator;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Supplier::class);
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $suppliers = $query->orderBy('code')->get();
        $cities = Supplier::select('city')->distinct()->pluck('city');

        return view('supplier.index', compact('suppliers', 'cities'));
    }
    
    public function create()
    {
        $this->authorize('create', Supplier::class);
        $autoCode = $this->generateCode(Supplier::class, 'SUP');
        return view('supplier.form', compact('autoCode'));
    }
    
    public function store(Request $request)
    {
        $this->authorize('create', Supplier::class);
        $request->validate([
            'code' => 'required|unique:suppliers',
            'name' => 'required',
            'company_name' => 'required',
            'npwp' => 'required|string|max:50',
            'product_code' => 'nullable',
            'product_type' => 'nullable',
            'city' => 'required',
            'phone' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        
        Supplier::create($request->all());
        
        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }
    
    public function edit(Supplier $supplier)
    {
        $this->authorize('update', $supplier);
        return view('supplier.form', compact('supplier'));
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('view', $supplier);
        return view('supplier.show', compact('supplier'));
    }
    
    public function update(Request $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);
        $request->validate([
            'code' => 'required|unique:suppliers,code,' . $supplier->id,
            'name' => 'required',
            'company_name' => 'required',
            'npwp' => 'required|string|max:50',
            'product_code' => 'nullable',
            'product_type' => 'nullable',
            'city' => 'required',
            'phone' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        
        $supplier->update($request->all());
        
        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diupdate');
    }
    
    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);
        $supplier->delete();
        
        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}