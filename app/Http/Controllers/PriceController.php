<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PriceController extends Controller
{
    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_group' => 'nullable|string|max:50',
            'date' => 'nullable|date',
        ]);

        $date = !empty($validated['date']) ? Carbon::parse($validated['date'])->toDateString() : null;
        $group = $validated['customer_group'] ?? null;

        $q = Price::query()->where('product_id', $validated['product_id']);

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

        $price = $q->orderByRaw('effective_date is null') // prefer non-null effective_date
            ->orderBy('effective_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'found' => (bool) $price,
            'price_large' => $price?->price_large,
            'price_small' => $price?->price_small,
            'discount' => $price?->discount ?? 0,
            'tax' => $price?->tax ?? 0,
        ]);
    }

    public function index()
    {
        $prices = Price::with('product')
            ->orderBy('effective_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('price.index', compact('prices'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('price.form', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
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
        return view('price.show', compact('price'));
    }

    public function edit(Price $price)
    {
        $products = Product::orderBy('name')->get();
        return view('price.form', compact('price', 'products'));
    }

    public function update(Request $request, Price $price)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
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
        $price->delete();
        return redirect()->route('price.index')->with('success', 'Harga berhasil dihapus');
    }
}

