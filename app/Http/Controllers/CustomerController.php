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
        $this->authorize('viewAny', Customer::class);
        $query = Customer::with('salesman');

        // RBAC Global Filter
        $user = auth()->user();
        $allowedIds = [];
        
        if ($user->role === 'sales') {
            $allowedIds = [$user->salesman_id];
            $query->where('salesman_id', $user->salesman_id);
        } elseif ($user->role === 'supervisor') {
            $supervisorSalesmanId = $user->salesman_id;
            $subordinateIds = \App\Models\Salesman::where('supervisor_id', $supervisorSalesmanId)->pluck('id')->toArray();
            $allowedIds = array_merge([$supervisorSalesmanId], $subordinateIds);
            $query->whereIn('salesman_id', $allowedIds);
        } elseif ($user->role === 'manager') {
            $managerSalesmanId = $user->salesman_id;
            $supervisorIds = \App\Models\Salesman::where('supervisor_id', $managerSalesmanId)->pluck('id')->toArray();
            $salesIds = \App\Models\Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            $allowedIds = array_merge([$managerSalesmanId], $supervisorIds, $salesIds);
            $query->whereIn('salesman_id', $allowedIds);
        }

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
        
        // Filter dropdown salesmen based on role
        if (in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $salesmen = Salesman::whereIn('id', $allowedIds)->orderBy('name')->get();
        } else {
            $salesmen = Salesman::orderBy('name')->get();
        }
        
        $groups = Customer::whereNotNull('group')->distinct()->pluck('group');

        return view('customer.index', compact('customers', 'salesmen', 'groups'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Customer::class);
        
        $user = auth()->user();
        if (in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            $salesmen = Salesman::whereIn('id', $allowedIds)->orderBy('name')->get();
        } else {
            $salesmen = Salesman::orderBy('name')->get();
        }

        // Cities from salesmen for the dynamic filter dropdown
        $cities = $salesmen->whereNotNull('city')->pluck('city')->unique()->sort()->values();

        // Pre-embed all salesman data as JSON for client-side filtering
        $allSalesmen = $salesmen->map(fn($s) => [
            'id'    => $s->id,
            'name'  => $s->name,
            'level' => strtoupper($s->level),
            'city'  => $s->city ?? '',
            'area'  => $s->area_display ?: ($s->city ?? $s->area),
        ]);

        $autoCode = $this->generateCode(Customer::class, 'CST');
        return view('customer.form', compact('salesmen', 'autoCode', 'cities', 'allSalesmen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Customer::class);

        $validated = $request->validate([
            'code' => 'required|unique:customers,code',
            'name' => 'required',
            'nik' => 'required|string|max:50',
            'npwp' => 'required|string|max:50',
            'address' => 'required',
            'city' => 'nullable|string|max:100',
            'phone' => 'required',
            'group' => 'nullable|string|max:50',
            'salesman_id' => 'nullable|exists:salesmen,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = auth()->user();
        if ($request->filled('salesman_id') && in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            if (!in_array($request->salesman_id, $allowedIds)) {
                return back()->withInput()->withErrors(['salesman_id' => 'Salesman yang dipilih harus berada dalam tim Anda.']);
            }
        }

        // Validate salesman operational area matches customer city
        if ($request->filled('salesman_id')) {
            $salesman = Salesman::find($request->salesman_id);
            if ($salesman) {
                $customerCity = trim($request->city);
                
                if ($salesman->level === 'manager') {
                    $areaObj = \App\Models\Area::where('city', 'like', $customerCity)->first();
                    if ($areaObj) {
                        if (strtolower(trim($salesman->area)) !== strtolower(trim($areaObj->province))) {
                            return back()->withInput()->withErrors(['salesman_id' => "Salesman Manager ini memiliki wilayah kerja di provinsi {$salesman->area}, sedangkan customer berada di provinsi {$areaObj->province}."]);
                        }
                    }
                } else {
                    $salesmanCity = trim($salesman->city);
                    if ($salesmanCity && strtolower(trim($customerCity)) !== strtolower($salesmanCity)) {
                        return back()->withInput()->withErrors(['salesman_id' => "Salesman ini memiliki wilayah kerja di kota {$salesmanCity}, sedangkan customer berada di kota {$customerCity}."]);
                    }
                }
            }
        }

        Customer::create($validated);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);
        $this->validateTeamAccess($customer);
        return view('customer.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);
        $this->validateTeamAccess($customer);
        
        $user = auth()->user();
        if (in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            $salesmen = Salesman::whereIn('id', $allowedIds)->orderBy('name')->get();
        } else {
            $salesmen = Salesman::orderBy('name')->get();
        }

        // Cities from salesmen for the dynamic filter dropdown
        $cities = $salesmen->whereNotNull('city')->pluck('city')->unique()->sort()->values();

        // Pre-embed all salesman data as JSON for client-side filtering
        $allSalesmen = $salesmen->map(fn($s) => [
            'id'    => $s->id,
            'name'  => $s->name,
            'level' => strtoupper($s->level),
            'city'  => $s->city ?? '',
            'area'  => $s->area_display ?: ($s->city ?? $s->area),
        ]);

        return view('customer.form', compact('customer', 'salesmen', 'cities', 'allSalesmen'));
    }

    private function getAllowedSalesmanIds($user)
    {
        if ($user->role === 'sales') {
            return [$user->salesman_id];
        } elseif ($user->role === 'supervisor') {
            $subordinateIds = Salesman::where('supervisor_id', $user->salesman_id)->pluck('id')->toArray();
            return array_merge([$user->salesman_id], $subordinateIds);
        } elseif ($user->role === 'manager') {
            $supervisorIds = Salesman::where('supervisor_id', $user->salesman_id)->pluck('id')->toArray();
            $salesIds = Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            return array_merge([$user->salesman_id], $supervisorIds, $salesIds);
        }
        return [];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);
        $this->validateTeamAccess($customer);

        $validated = $request->validate([
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required',
            'nik' => 'required|string|max:50',
            'npwp' => 'required|string|max:50',
            'address' => 'required',
            'city' => 'nullable|string|max:100',
            'phone' => 'required',
            'group' => 'nullable|string|max:50',
            'salesman_id' => 'nullable|exists:salesmen,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = auth()->user();
        if ($request->filled('salesman_id') && in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            if (!in_array($request->salesman_id, $allowedIds)) {
                return back()->withInput()->withErrors(['salesman_id' => 'Salesman yang dipilih harus berada dalam tim Anda.']);
            }
        }

        // Validate salesman operational area matches customer city
        if ($request->filled('salesman_id')) {
            $salesman = Salesman::find($request->salesman_id);
            if ($salesman) {
                $customerCity = trim($request->city);
                
                if ($salesman->level === 'manager') {
                    $areaObj = \App\Models\Area::where('city', 'like', $customerCity)->first();
                    if ($areaObj) {
                        if (strtolower(trim($salesman->area)) !== strtolower(trim($areaObj->province))) {
                            return back()->withInput()->withErrors(['salesman_id' => "Salesman Manager ini memiliki wilayah kerja di provinsi {$salesman->area}, sedangkan customer berada di provinsi {$areaObj->province}."]);
                        }
                    }
                } else {
                    $salesmanCity = trim($salesman->city);
                    if ($salesmanCity && strtolower(trim($customerCity)) !== strtolower($salesmanCity)) {
                        return back()->withInput()->withErrors(['salesman_id' => "Salesman ini memiliki wilayah kerja di kota {$salesmanCity}, sedangkan customer berada di kota {$customerCity}."]);
                    }
                }
            }
        }

        $customer->update($validated);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);
        $this->validateTeamAccess($customer);
        
        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil dihapus');
    }

    private function validateTeamAccess(Customer $customer)
    {
        $user = auth()->user();
        if (in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            abort_unless(in_array($customer->salesman_id, $allowedIds), 403, 'Anda tidak memiliki hak akses untuk data customer di luar wilayah kerja tim Anda.');
        }
    }

    /**
     * API: Return salesmen filtered by city (for dynamic PIC dropdown).
     */
    public function salesmenByCity(Request $request)
    {
        $user = auth()->user();
        $city = trim($request->city ?? '');

        if (in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            $query = Salesman::whereIn('id', $allowedIds);
        } else {
            $query = Salesman::query();
        }

        if ($city) {
            $query->whereRaw('LOWER(city) = ?', [strtolower($city)]);
        }

        $salesmen = $query->orderBy('name')->get()->map(fn($s) => [
            'id'    => $s->id,
            'name'  => $s->name,
            'level' => strtoupper($s->level),
            'area'  => $s->area_display ?: ($s->city ?? $s->area),
        ]);

        return response()->json($salesmen);
    }
}
