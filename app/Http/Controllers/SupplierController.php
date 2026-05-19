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

        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = \App\Models\Area::where('province', $province)->pluck('city')->unique()->toArray();
            $query->whereIn('city', $allowedCities);
        }

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
        
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = \App\Models\Area::where('province', $province)->pluck('city')->unique()->toArray();
            $cities = Supplier::whereIn('city', $allowedCities)->select('city')->distinct()->pluck('city');
        } else {
            $cities = Supplier::select('city')->distinct()->pluck('city');
        }

        return view('supplier.index', compact('suppliers', 'cities'));
    }
    
    public function create()
    {
        $this->authorize('create', Supplier::class);
        $autoCode = $this->generateCode(Supplier::class, 'SUP');
        
        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        
        if ($province) {
            $provinces = collect([$province]);
            $areasGrouped = \App\Models\Area::where('province', $province)
                ->select('province', 'city')
                ->distinct()
                ->get()
                ->groupBy('province')
                ->map(fn($items) => $items->pluck('city')->unique()->sort()->values());
        } else {
            $provinces = \App\Models\Area::select('province')
                ->distinct()
                ->orderBy('province')
                ->pluck('province');
                
            $areasGrouped = \App\Models\Area::select('province', 'city')
                ->distinct()
                ->get()
                ->groupBy('province')
                ->map(fn($items) => $items->pluck('city')->unique()->sort()->values());
        }
        
        return view('supplier.form', compact('autoCode', 'provinces', 'areasGrouped'));
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
        
        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = \App\Models\Area::where('province', $province)->pluck('city')->unique()->toArray();
            if (!in_array($request->city, $allowedCities)) {
                return back()->withInput()->withErrors(['city' => 'Kota suplier harus berada di provinsi Anda (' . $province . ').']);
            }
        }
        
        Supplier::create($request->all());
        
        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }
    
    public function edit(Supplier $supplier)
    {
        $this->authorize('update', $supplier);
        $this->validateTeamAccess($supplier);

        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        
        if ($province) {
            $provinces = collect([$province]);
            $areasGrouped = \App\Models\Area::where('province', $province)
                ->select('province', 'city')
                ->distinct()
                ->get()
                ->groupBy('province')
                ->map(fn($items) => $items->pluck('city')->unique()->sort()->values());
        } else {
            $provinces = \App\Models\Area::select('province')
                ->distinct()
                ->orderBy('province')
                ->pluck('province');
                
            $areasGrouped = \App\Models\Area::select('province', 'city')
                ->distinct()
                ->get()
                ->groupBy('province')
                ->map(fn($items) => $items->pluck('city')->unique()->sort()->values());
        }

        $supplierProvince = '';
        if (!empty($supplier->city)) {
            $area = \App\Models\Area::where('city', $supplier->city)->first();
            if ($area) {
                $supplierProvince = $area->province;
            }
        }

        return view('supplier.form', compact('supplier', 'provinces', 'areasGrouped', 'supplierProvince'));
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('view', $supplier);
        $this->validateTeamAccess($supplier);
        return view('supplier.show', compact('supplier'));
    }
    
    public function update(Request $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);
        $this->validateTeamAccess($supplier);
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
        
        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = \App\Models\Area::where('province', $province)->pluck('city')->unique()->toArray();
            if (!in_array($request->city, $allowedCities)) {
                return back()->withInput()->withErrors(['city' => 'Kota suplier harus berada di provinsi Anda (' . $province . ').']);
            }
        }
        
        $supplier->update($request->all());
        
        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diupdate');
    }
    
    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);
        $this->validateTeamAccess($supplier);
        
        if ($supplier->products()->exists()) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak dapat dihapus karena masih memiliki data produk yang terkait.');
        }

        $supplier->delete();
        
        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus');
    }

    private function validateTeamAccess(Supplier $supplier)
    {
        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = \App\Models\Area::where('province', $province)->pluck('city')->unique()->toArray();
            abort_unless(in_array($supplier->city, $allowedCities), 403, 'Anda tidak memiliki hak akses untuk data supplier di luar wilayah kerja tim Anda.');
        }
    }

    private function getProvinceForUser($user)
    {
        if (!$user || !$user->salesman) {
            return null;
        }

        if ($user->role === 'manager') {
            return $user->salesman->area;
        } elseif ($user->role === 'supervisor') {
            $manager = \App\Models\Salesman::find($user->salesman->supervisor_id);
            return $manager ? $manager->area : null;
        } elseif ($user->role === 'sales') {
            $supervisor = \App\Models\Salesman::find($user->salesman->supervisor_id);
            if ($supervisor) {
                $manager = \App\Models\Salesman::find($supervisor->supervisor_id);
                return $manager ? $manager->area : null;
            }
        }
        return null;
    }
}