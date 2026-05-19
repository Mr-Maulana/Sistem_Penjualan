<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Price;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PriceController extends Controller
{
    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,code',
            'customer_group' => 'nullable|string|max:50',
            'date' => 'nullable|date',
        ]);

        $date = !empty($validated['date']) ? Carbon::parse($validated['date'])->toDateString() : null;
        $group = $validated['customer_group'] ?? null;

        $q = Price::query()->where('product_code', $validated['product_id']);

        if ($group !== null && $group !== '') {
            $q->where('customer_group', $group);
        } else {
            $q->whereNull('customer_group');
        }

        if ($date) {
            $q->where(function ($qq) use ($date) {
                $qq->whereNull('effective_date')->orWhere('effective_date', '<=', $date);
            });
        }

        $price = $q->orderByRaw('effective_date is null')
            ->orderBy('effective_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'found'        => (bool) $price,
            'price_large'  => $price?->price_large,
            'price_small'  => $price?->price_small,
            'discount'     => $price?->discount ?? 0,
            'tax'          => $price?->tax ?? 0,
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Price::class);

        $user = auth()->user();
        $allowedProductCodes = null;
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = Area::where('province', $province)->pluck('city')->unique()->toArray();
            $allowedSupplierCodes = Supplier::whereIn('city', $allowedCities)->pluck('code')->toArray();
            $allowedProductCodes = Product::whereIn('supplier_code', $allowedSupplierCodes)->pluck('code')->toArray();
        }

        $query = Price::with('product.supplier');
        if ($allowedProductCodes !== null) {
            $query->whereIn('product_code', $allowedProductCodes);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group')) {
            $query->where('customer_group', $request->group);
        }

        $prices = $query->orderBy('effective_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $groups = Price::whereNotNull('customer_group')->distinct()->pluck('customer_group');

        return view('price.index', compact('prices', 'groups'));
    }

    public function create()
    {
        $this->authorize('create', Price::class);

        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = Area::where('province', $province)->pluck('city')->unique()->toArray();
            $allowedSupplierCodes = Supplier::whereIn('city', $allowedCities)->pluck('code')->toArray();
            $products = Product::whereIn('supplier_code', $allowedSupplierCodes)->orderBy('name')->get();
        } else {
            $products = Product::orderBy('name')->get();
        }

        return view('price.form', compact('products'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Price::class);
        $validated = $request->validate([
            'product_code' => 'required|exists:products,code',
            'customer_group' => 'nullable|string|max:50',
            'price_large' => 'nullable|numeric|min:0',
            'price_small' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'effective_date' => 'nullable|date',
        ]);

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['tax'] = $validated['tax'] ?? 0;

        Price::create($validated);

        return redirect()->route('price.index')->with('success', 'Harga berhasil ditambahkan');
    }

    public function show(Price $price)
    {
        $this->authorize('view', $price);
        return view('price.show', compact('price'));
    }

    public function edit(Price $price)
    {
        $this->authorize('update', $price);
        $this->validateTeamAccess($price);

        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = Area::where('province', $province)->pluck('city')->unique()->toArray();
            $allowedSupplierCodes = Supplier::whereIn('city', $allowedCities)->pluck('code')->toArray();
            $products = Product::whereIn('supplier_code', $allowedSupplierCodes)->orderBy('name')->get();
        } else {
            $products = Product::orderBy('name')->get();
        }

        return view('price.form', compact('price', 'products'));
    }

    public function update(Request $request, Price $price)
    {
        $this->authorize('update', $price);
        $this->validateTeamAccess($price);
        $validated = $request->validate([
            'product_code' => 'required|exists:products,code',
            'customer_group' => 'nullable|string|max:50',
            'price_large' => 'nullable|numeric|min:0',
            'price_small' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'effective_date' => 'nullable|date',
        ]);

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['tax'] = $validated['tax'] ?? 0;

        $price->update($validated);

        return redirect()->route('price.index')->with('success', 'Harga berhasil diupdate');
    }

    public function destroy(Price $price)
    {
        $this->authorize('delete', $price);
        $this->validateTeamAccess($price);
        
        $hasTransactions = \App\Models\SaleItem::where('product_code', $price->product_code)->exists();
        if ($hasTransactions) {
            return redirect()->route('price.index')
                ->with('error', 'Harga tidak dapat dihapus karena produk terkait sudah memiliki data transaksi.');
        }

        $price->delete();
        return redirect()->route('price.index')->with('success', 'Harga berhasil dihapus');
    }

    private function validateTeamAccess(Price $price)
    {
        $user = auth()->user();
        $province = $this->getProvinceForUser($user);
        if ($province) {
            $allowedCities = Area::where('province', $province)->pluck('city')->unique()->toArray();
            $allowedSupplierCodes = Supplier::whereIn('city', $allowedCities)->pluck('code')->toArray();
            $allowedProductCodes = Product::whereIn('supplier_code', $allowedSupplierCodes)->pluck('code')->toArray();
            abort_unless(in_array($price->product_code, $allowedProductCodes), 403,
                'Anda tidak memiliki hak akses untuk harga di luar wilayah kerja tim Anda.');
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
