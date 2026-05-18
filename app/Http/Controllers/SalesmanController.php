<?php

namespace App\Http\Controllers;

use App\Models\Salesman;
use Illuminate\Http\Request;
use App\Traits\CodeGenerator;

class SalesmanController extends Controller
{
    use CodeGenerator;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Salesman::class);
        $query = Salesman::with(['supervisor']);

        if (auth()->user()) {
            $user = auth()->user();
            if ($user->role === 'supervisor') {
                $query->where('supervisor_id', $user->salesman_id);
            } elseif ($user->role === 'manager') {
                $managerSalesmanId = $user->salesman_id;
                $supervisorIds = Salesman::where('supervisor_id', $managerSalesmanId)->pluck('id')->toArray();
                $salesIds = Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
                $allowedIds = array_merge([$managerSalesmanId], $supervisorIds, $salesIds);
                $query->whereIn('id', $allowedIds);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhereHas('areaData', function($aq) use ($search) {
                      $aq->where('name', 'like', "%{$search}%")
                         ->orWhere('city', 'like', "%{$search}%")
                         ->orWhere('province', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $salesmen = $query->orderBy('code')->get();
        return view('salesman.index', compact('salesmen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Salesman::class);
        $autoCode = $this->generateCode(Salesman::class, 'SLS');
        
        $user = auth()->user();
        if ($user && $user->role === 'manager') {
            $supervisors = Salesman::where(function($q) use ($user) {
                $q->where('supervisor_id', $user->salesman_id)
                  ->orWhere('id', $user->salesman_id);
            })->whereIn('level', ['supervisor', 'manager'])->orderBy('name')->get();
        } else {
            $supervisors = Salesman::whereIn('level', ['supervisor', 'manager'])
                ->orderBy('name')->get();
        }
        
        $areas = \App\Models\Area::orderBy('city')->orderBy('code')->get();
        return view('salesman.form', compact('autoCode', 'supervisors', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Salesman::class);
        
        $level = $request->input('level', 'sales');
        
        $rules = [
            'code' => 'required|unique:salesmen,code',
            'name' => 'required',
            'nik' => 'required|string|max:50',
            'npwp' => 'required|string|max:50',
            'email' => 'nullable|email',
            'photo_file' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'phone' => 'required',
            'target' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'level' => 'required|in:sales,supervisor,manager',
            'supervisor_id' => 'nullable|exists:salesmen,id',
        ];

        if ($level === 'manager') {
            $rules['area'] = 'required|string'; // Stores Province
            $rules['city'] = 'nullable|string';
        } elseif ($level === 'supervisor') {
            $rules['area'] = 'nullable|string';
            $rules['city'] = 'required|string'; // Stores City
        } else {
            $rules['area'] = 'required|exists:areas,code'; // Stores Kecamatan
            $rules['city'] = 'required|string'; // Stores City
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('photo_file')) {
            $validated['photo'] = $request->file('photo_file')->store('salesmen', 'public');
        }

        // Enforcement: Supervisor must have a Manager
        if ($validated['level'] === 'supervisor' && empty($validated['supervisor_id'])) {
            return back()->withInput()->withErrors(['supervisor_id' => 'Supervisor wajib memilih Manager sebagai atasan.']);
        }

        // Validation for hierarchy
        if ($validated['supervisor_id']) {
            $superior = Salesman::find($validated['supervisor_id']);
            if ($validated['level'] === 'sales') {
                if (!in_array($superior->level, ['supervisor', 'manager'])) {
                    return back()->withInput()->withErrors(['supervisor_id' => 'Sales hanya bisa dibawahi oleh Supervisor atau Manager.']);
                }
                
                // If Sales is under a Supervisor, check if they are in the same city
                if ($superior->level === 'supervisor' && $superior->city !== $validated['city']) {
                    return back()->withInput()->withErrors(['supervisor_id' => "Atasan (Supervisor) harus berada di kota yang sama ({$superior->city})."]);
                }
                // If Sales is under a Manager directly, check if they are in the same province
                if ($superior->level === 'manager') {
                    $areaObj = \App\Models\Area::where('city', $validated['city'])->first();
                    if ($areaObj && $areaObj->province !== $superior->area) {
                        return back()->withInput()->withErrors(['supervisor_id' => "Atasan (Manager) harus berada di provinsi yang sama ({$superior->area})."]);
                    }
                }
            }
            if ($validated['level'] === 'supervisor') {
                if ($superior->level !== 'manager') {
                    return back()->withInput()->withErrors(['supervisor_id' => 'Supervisor hanya bisa dibawahi oleh Manager.']);
                }
                
                // Check if Supervisor's city belongs to Manager's province
                $areaObj = \App\Models\Area::where('city', $validated['city'])->first();
                if ($areaObj && $areaObj->province !== $superior->area) {
                    return back()->withInput()->withErrors(['supervisor_id' => "Atasan (Manager) harus berada di provinsi yang sama ({$superior->area})."]);
                }
            }
            if ($validated['level'] === 'manager') {
                return back()->withInput()->withErrors(['supervisor_id' => 'Manager tidak boleh memiliki atasan.']);
            }
        }

        $salesman = Salesman::create($validated);
        $this->bridgeToUser($salesman);

        return redirect()->route('salesman.index')->with('success', 'Data Sales berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salesman $salesman)
    {
        $this->authorize('view', $salesman);
        $this->validateTeamAccess($salesman);
        
        $salesman->load(['supervisor', 'subordinates']);
        return view('salesman.show', compact($salesman->id ? 'salesman' : []))->with('salesman', $salesman);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salesman $salesman)
    {
        $this->authorize('update', $salesman);
        $this->validateTeamAccess($salesman);

        $user = auth()->user();
        if ($user->role === 'manager') {
            $supervisors = Salesman::where(function($q) use ($user) {
                $q->where('supervisor_id', $user->salesman_id)
                  ->orWhere('id', $user->salesman_id);
            })->whereIn('level', ['supervisor', 'manager'])
              ->where('id', '!=', $salesman->id)
              ->orderBy('name')->get();
        } else {
            $supervisors = Salesman::whereIn('level', ['supervisor', 'manager'])
                ->where('id', '!=', $salesman->id)
                ->orderBy('name')->get();
        }

        $areas = \App\Models\Area::orderBy('city')->orderBy('code')->get();
        return view('salesman.form', compact('salesman', 'supervisors', 'areas'));
    }

    public function update(Request $request, Salesman $salesman)
    {
        $this->authorize('update', $salesman);
        $this->validateTeamAccess($salesman);
        
        $level = $request->input('level', 'sales');
        
        $rules = [
            'code' => 'required|unique:salesmen,code,' . $salesman->id,
            'name' => 'required',
            'nik' => 'required|string|max:50',
            'npwp' => 'required|string|max:50',
            'email' => 'nullable|email',
            'photo_file' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'phone' => 'required',
            'target' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'level' => 'required|in:sales,supervisor,manager',
            'supervisor_id' => 'nullable|exists:salesmen,id',
        ];

        if ($level === 'manager') {
            $rules['area'] = 'required|string'; // Stores Province
            $rules['city'] = 'nullable|string';
        } elseif ($level === 'supervisor') {
            $rules['area'] = 'nullable|string';
            $rules['city'] = 'required|string'; // Stores City
        } else {
            $rules['area'] = 'required|exists:areas,code'; // Stores Kecamatan
            $rules['city'] = 'required|string'; // Stores City
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('photo_file')) {
            if ($salesman->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($salesman->photo);
            }
            $validated['photo'] = $request->file('photo_file')->store('salesmen', 'public');
        }

        // Enforcement: Supervisor must have a Manager
        if ($validated['level'] === 'supervisor' && empty($validated['supervisor_id'])) {
            return back()->withInput()->withErrors(['supervisor_id' => 'Supervisor wajib memilih Manager sebagai atasan.']);
        }

        if ($validated['supervisor_id']) {
            $superior = Salesman::find($validated['supervisor_id']);
            if ($validated['level'] === 'sales') {
                if (!in_array($superior->level, ['supervisor', 'manager'])) {
                    return back()->withInput()->withErrors(['supervisor_id' => 'Sales hanya bisa dibawahi oleh Supervisor atau Manager.']);
                }
                
                // If Sales is under a Supervisor, check if they are in the same city
                if ($superior->level === 'supervisor' && $superior->city !== $validated['city']) {
                    return back()->withInput()->withErrors(['supervisor_id' => "Atasan (Supervisor) harus berada di kota yang sama ({$superior->city})."]);
                }
                // If Sales is under a Manager directly, check if they are in the same province
                if ($superior->level === 'manager') {
                    $areaObj = \App\Models\Area::where('city', $validated['city'])->first();
                    if ($areaObj && $areaObj->province !== $superior->area) {
                        return back()->withInput()->withErrors(['supervisor_id' => "Atasan (Manager) harus berada di provinsi yang sama ({$superior->area})."]);
                    }
                }
            }
            if ($validated['level'] === 'supervisor') {
                if ($superior->level !== 'manager') {
                    return back()->withInput()->withErrors(['supervisor_id' => 'Supervisor hanya bisa dibawahi oleh Manager.']);
                }
                
                // Check if Supervisor's city belongs to Manager's province
                $areaObj = \App\Models\Area::where('city', $validated['city'])->first();
                if ($areaObj && $areaObj->province !== $superior->area) {
                    return back()->withInput()->withErrors(['supervisor_id' => "Atasan (Manager) harus berada di provinsi yang sama ({$superior->area})."]);
                }
            }
            if ($validated['level'] === 'manager') {
                return back()->withInput()->withErrors(['supervisor_id' => 'Manager tidak boleh memiliki atasan.']);
            }
        }

        $salesman->update($validated);
        $this->bridgeToUser($salesman);

        return redirect()->route('salesman.index')->with('success', 'Data Sales berhasil diupdate');
    }

    private function bridgeToUser(Salesman $salesman)
    {
        $user = \App\Models\User::where('salesman_id', $salesman->id)->first();
        // Updated mapping: manager level maps to manager role
        $expectedRole = $salesman->level; // 'manager', 'supervisor', or 'sales'
        
        if (!$user) {
            $email = $salesman->email ?: \Illuminate\Support\Str::slug($salesman->name) . '@perusahaan.com';
            $baseEmail = $email;
            $counter = 1;
            while(\App\Models\User::where('email', $email)->exists()) {
                $parts = explode('@', $baseEmail);
                $email = $parts[0] . $counter . '@' . $parts[1];
                $counter++;
            }
 
            \App\Models\User::create([
                'name' => $salesman->name,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => $expectedRole,
                'salesman_id' => $salesman->id,
                'status' => 'active',
                'profile_photo_path' => $salesman->photo,
            ]);
        } else {
            $user->update([
                'name' => $salesman->name,
                'role' => $expectedRole,
                'profile_photo_path' => $salesman->photo,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salesman $salesman)
    {
        $this->authorize('delete', $salesman);
        $this->validateTeamAccess($salesman);

        $salesman->delete();

        return redirect()->route('salesman.index')
            ->with('success', 'Salesman berhasil dihapus');
    }

    private function validateTeamAccess(Salesman $salesman)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if ($user->role === 'supervisor') {
            $allowedIds = [$user->salesman_id];
            $subordinateIds = Salesman::where('supervisor_id', $user->salesman_id)->pluck('id')->toArray();
            $allowedIds = array_merge($allowedIds, $subordinateIds);
            abort_unless(in_array($salesman->id, $allowedIds), 403, 'Anda tidak memiliki hak akses untuk data salesman di luar tim Anda.');
        } elseif ($user->role === 'manager') {
            $managerSalesmanId = $user->salesman_id;
            $supervisorIds = Salesman::where('supervisor_id', $managerSalesmanId)->pluck('id')->toArray();
            $salesIds = Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            $allowedIds = array_merge([$managerSalesmanId], $supervisorIds, $salesIds);
            abort_unless(in_array($salesman->id, $allowedIds), 403, 'Anda tidak memiliki hak akses untuk data salesman di luar tim Anda.');
        }
    }
}
