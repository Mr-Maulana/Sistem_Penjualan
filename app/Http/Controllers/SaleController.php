<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['customer', 'salesman'])->orderBy('date', 'desc')->get();
        return view('sale.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $salesmen = Salesman::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('sale.form', compact('customers', 'salesmen', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:sales,invoice_number',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'salesman_id' => 'required|exists:salesmen,id',
            'payment_term' => 'nullable|string|max:50',
            'down_payment' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,unpaid,partial',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.bonus' => 'nullable|integer|min:0',
        ]);

        $validated['down_payment'] = $validated['down_payment'] ?? 0;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['tax'] = $validated['tax'] ?? 0;

        $items = $validated['items'];
        unset($validated['items']);

        try {
            DB::transaction(function () use ($validated, $items) {
                $subtotal = 0;

                foreach ($items as $item) {
                    $lineDiscount = (float) ($item['discount'] ?? 0);
                    $lineSubtotal = ((int) $item['quantity']) * ((float) $item['price']) - $lineDiscount;
                    $subtotal += max(0, $lineSubtotal);
                }

                $sale = Sale::create(array_merge($validated, [
                    'subtotal' => $subtotal,
                    'total' => max(0, $subtotal - (float) $validated['discount'] + (float) $validated['tax']),
                ]));

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $qty = (int) $item['quantity'];
                    $bonus = (int) ($item['bonus'] ?? 0);
                    $stockOut = $qty + $bonus;

                    if ($product->stock < $stockOut) {
                        throw new \RuntimeException("Stok produk {$product->name} tidak mencukupi. Stok: {$product->stock}, Dibutuhkan: {$stockOut}");
                    }

                    $lineDiscount = (float) ($item['discount'] ?? 0);
                    $lineSubtotal = $qty * ((float) $item['price']) - $lineDiscount;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => (float) $item['price'],
                        'discount' => $lineDiscount,
                        'bonus' => $bonus,
                        'subtotal' => max(0, $lineSubtotal),
                    ]);

                    $product->decrement('stock', $stockOut);
                }
            });
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['items' => $e->getMessage()]);
        }

        return redirect()->route('sale.index')
            ->with('success', 'Penjualan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('sale.index');
    }

    public function print(Sale $sale)
    {
        $sale->load(['customer', 'salesman', 'items.product']);

        $pdf = Pdf::loadView('sale.invoice-pdf', [
            'sale' => $sale,
        ])->setPaper('a4');

        return $pdf->stream("invoice-{$sale->invoice_number}.pdf");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $customers = Customer::orderBy('name')->get();
        $salesmen = Salesman::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $sale->load(['items.product']);
        return view('sale.form', compact('sale', 'customers', 'salesmen', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:sales,invoice_number,' . $sale->id,
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'salesman_id' => 'required|exists:salesmen,id',
            'payment_term' => 'nullable|string|max:50',
            'down_payment' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,unpaid,partial',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.bonus' => 'nullable|integer|min:0',
        ]);

        $validated['down_payment'] = $validated['down_payment'] ?? 0;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['tax'] = $validated['tax'] ?? 0;

        $items = $validated['items'];
        unset($validated['items']);

        try {
            DB::transaction(function () use ($sale, $validated, $items) {
                $sale->load('items');

            // Restore stock from existing items
            foreach ($sale->items as $existing) {
                $product = Product::lockForUpdate()->find($existing->product_id);
                if ($product) {
                    $product->increment('stock', (int) $existing->quantity + (int) ($existing->bonus ?? 0));
                }
            }

            $sale->items()->delete();

            $subtotal = 0;
            foreach ($items as $item) {
                $lineDiscount = (float) ($item['discount'] ?? 0);
                $lineSubtotal = ((int) $item['quantity']) * ((float) $item['price']) - $lineDiscount;
                $subtotal += max(0, $lineSubtotal);
            }

            $sale->update(array_merge($validated, [
                'subtotal' => $subtotal,
                'total' => max(0, $subtotal - (float) $validated['discount'] + (float) $validated['tax']),
            ]));

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $qty = (int) $item['quantity'];
                    $bonus = (int) ($item['bonus'] ?? 0);
                    $stockOut = $qty + $bonus;

                    if ($product->stock < $stockOut) {
                        throw new \RuntimeException("Stok produk {$product->name} tidak mencukupi. Stok: {$product->stock}, Dibutuhkan: {$stockOut}");
                    }

                    $lineDiscount = (float) ($item['discount'] ?? 0);
                    $lineSubtotal = $qty * ((float) $item['price']) - $lineDiscount;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => (float) $item['price'],
                        'discount' => $lineDiscount,
                        'bonus' => $bonus,
                        'subtotal' => max(0, $lineSubtotal),
                    ]);

                    $product->decrement('stock', $stockOut);
                }
            });
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['items' => $e->getMessage()]);
        }

        return redirect()->route('sale.index')
            ->with('success', 'Penjualan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->load('items');
            foreach ($sale->items as $existing) {
                $product = Product::lockForUpdate()->find($existing->product_id);
                if ($product) {
                    $product->increment('stock', (int) $existing->quantity + (int) ($existing->bonus ?? 0));
                }
            }
            $sale->delete();
        });

        return redirect()->route('sale.index')
            ->with('success', 'Penjualan berhasil dihapus');
    }
}
