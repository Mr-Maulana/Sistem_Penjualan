<?php
namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Traits\CodeGenerator;

class DistributorController extends Controller
{
    use CodeGenerator;

    public function index(Request $request)
    {
        $query = Distributor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $distributors = $query->orderBy('code')->get();
        $cities = Distributor::select('city')->distinct()->pluck('city');

        return view('distributor.index', compact('distributors', 'cities'));
    }
    
    public function create()
    {
        $autoCode = $this->generateCode(Distributor::class, 'DBR');
        return view('distributor.form', compact('autoCode'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:distributors',
            'name' => 'required',
            'city' => 'required',
            'phone' => 'required',
        ]);
        
        Distributor::create($request->all());
        
        return redirect()->route('distributor.index')
            ->with('success', 'Distributor berhasil ditambahkan');
    }
    
    public function edit(Distributor $distributor)
    {
        return view('distributor.form', compact('distributor'));
    }

    public function show(Distributor $distributor)
    {
        return view('distributor.show', compact('distributor'));
    }
    
    public function update(Request $request, Distributor $distributor)
    {
        $request->validate([
            'code' => 'required|unique:distributors,code,' . $distributor->id,
            'name' => 'required',
            'city' => 'required',
            'phone' => 'required',
        ]);
        
        $distributor->update($request->all());
        
        return redirect()->route('distributor.index')
            ->with('success', 'Distributor berhasil diupdate');
    }
    
    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        
        return redirect()->route('distributor.index')
            ->with('success', 'Distributor berhasil dihapus');
    }
}