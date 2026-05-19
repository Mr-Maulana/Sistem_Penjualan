<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Salesman;
use App\Models\Product;
use App\Services\CashFlowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\CodeGenerator;

class SaleController extends Controller
{
    use CodeGenerator;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = \App\Models\SaleHistory::with(['customer', 'salesman', 'sale'])->orderBy('date', 'desc')->orderBy('id', 'desc');

        // Logic Filter
        if (auth()->user()->role === 'sales') {
            $query->where('salesman_id', auth()->user()->salesman_id);
        } elseif (auth()->user()->role === 'supervisor') {
            $supervisorSalesmanId = auth()->user()->salesman_id;
            
            // Get subordinates IDs (Sales)
            $subordinateIds = \App\Models\Salesman::where('supervisor_id', $supervisorSalesmanId)->pluck('id')->toArray();
            
            // Can see own sales + subordinates sales
            $allowedIds = array_merge([$supervisorSalesmanId], $subordinateIds);
            $query->whereIn('salesman_id', $allowedIds);
        } elseif (auth()->user()->role === 'manager') {
            $managerSalesmanId = auth()->user()->salesman_id;
            
            // Get subordinates (Supervisors)
            $supervisorIds = \App\Models\Salesman::where('supervisor_id', $managerSalesmanId)->pluck('id')->toArray();
            
            // Get their subordinates (Sales)
            $salesIds = \App\Models\Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            
            // Can see own sales + supervisors + sales
            $allowedIds = array_merge([$managerSalesmanId], $supervisorIds, $salesIds);
            $query->whereIn('salesman_id', $allowedIds);
        }

        $sales = $query->get();
        return view('sale.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        $salesmenQuery = Salesman::orderBy('name');
        if (auth()->user()->role === 'sales') {
            $salesmenQuery->where('id', auth()->user()->salesman_id);
        } elseif (auth()->user()->role === 'supervisor') {
            $supervisorSalesmanId = auth()->user()->salesman_id;
            $subordinateIds = Salesman::where('supervisor_id', $supervisorSalesmanId)->pluck('id')->toArray();
            $allowedIds = array_merge([$supervisorSalesmanId], $subordinateIds);
            $salesmenQuery->whereIn('id', $allowedIds);
        } elseif (auth()->user()->role === 'manager') {
            $managerSalesmanId = auth()->user()->salesman_id;
            $supervisorIds = Salesman::where('supervisor_id', $managerSalesmanId)->pluck('id')->toArray();
            $salesIds = Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            $allowedIds = array_merge([$managerSalesmanId], $supervisorIds, $salesIds);
            $salesmenQuery->whereIn('id', $allowedIds);
        }
        $salesmen = $salesmenQuery->get();

        $autoInvoice = $this->generateDatedCode(Sale::class, 'INV');
        return view('sale.form', compact('customers', 'salesmen', 'products', 'autoInvoice'));
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
            'items.*.product_code' => 'required|exists:products,code',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.bonus' => 'nullable|integer|min:0',
        ]);

        // Force salesman_id for Sales role
        if (auth()->user()->role === 'sales') {
            $validated['salesman_id'] = auth()->user()->salesman_id;
        }

        $user = auth()->user();
        if (in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            if (!in_array($validated['salesman_id'], $allowedIds)) {
                return back()->withInput()->withErrors(['salesman_id' => 'Salesman yang dipilih harus berada dalam tim Anda.']);
            }
        }

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
                    $product = Product::where('code', $item['product_code'])->lockForUpdate()->firstOrFail();
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
                        'product_code' => $product->code,
                        'quantity' => $qty,
                        'price' => (float) $item['price'],
                        'discount' => $lineDiscount,
                        'bonus' => $bonus,
                        'subtotal' => max(0, $lineSubtotal),
                    ]);

                    $product->decrement('stock', $stockOut);
                }

                // Trigger CashFlow sync
                $cashFlowService = new \App\Services\CashFlowService();
                $cashFlowService->syncFromSale($sale);

                // Log into sale_histories
                \App\Models\SaleHistory::create([
                    'sale_id' => $sale->id,
                    'invoice_number' => $sale->invoice_number,
                    'date' => $sale->date,
                    'customer_id' => $sale->customer_id,
                    'salesman_id' => $sale->salesman_id,
                    'subtotal' => $sale->subtotal,
                    'discount' => $sale->discount,
                    'tax' => $sale->tax,
                    'total' => $sale->total,
                    'status' => $sale->status,
                    'notes' => $sale->notes,
                ]);
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
    public function show(Sale $sale)
    {
        $this->authorize('view', $sale);
        $this->validateTeamAccess($sale);
        $sale->load(['customer', 'salesman', 'items.product']);
        return view('sale.show', compact('sale'));
    }

    public function print(Sale $sale)
    {
        $this->validateTeamAccess($sale);
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
        $this->authorize('update', $sale);
        $this->validateTeamAccess($sale);
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        $salesmenQuery = Salesman::orderBy('name');
        if (auth()->user()->role === 'sales') {
            $salesmenQuery->where('id', auth()->user()->salesman_id);
        } elseif (auth()->user()->role === 'supervisor') {
            $supervisorSalesmanId = auth()->user()->salesman_id;
            $subordinateIds = Salesman::where('supervisor_id', $supervisorSalesmanId)->pluck('id')->toArray();
            $allowedIds = array_merge([$supervisorSalesmanId], $subordinateIds);
            $salesmenQuery->whereIn('id', $allowedIds);
        } elseif (auth()->user()->role === 'manager') {
            $managerSalesmanId = auth()->user()->salesman_id;
            $supervisorIds = Salesman::where('supervisor_id', $managerSalesmanId)->pluck('id')->toArray();
            $salesIds = Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            $allowedIds = array_merge([$managerSalesmanId], $supervisorIds, $salesIds);
            $salesmenQuery->whereIn('id', $allowedIds);
        }
        $salesmen = $salesmenQuery->get();

        $sale->load(['items.product']);
        return view('sale.form', compact('sale', 'customers', 'salesmen', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $this->authorize('update', $sale);
        $this->validateTeamAccess($sale);
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
            'items.*.product_code' => 'required|exists:products,code',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.bonus' => 'nullable|integer|min:0',
        ]);

        // Force salesman_id for Sales role
        if (auth()->user()->role === 'sales') {
            $validated['salesman_id'] = auth()->user()->salesman_id;
        }

        $user = auth()->user();
        if (in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            if (!in_array($validated['salesman_id'], $allowedIds)) {
                return back()->withInput()->withErrors(['salesman_id' => 'Salesman yang dipilih harus berada dalam tim Anda.']);
            }
        }

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
                $product = Product::where('code', $existing->product_code)->lockForUpdate()->first();
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
                    $product = Product::where('code', $item['product_code'])->lockForUpdate()->firstOrFail();
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
                        'product_code' => $product->code,
                        'quantity' => $qty,
                        'price' => (float) $item['price'],
                        'discount' => $lineDiscount,
                        'bonus' => $bonus,
                        'subtotal' => max(0, $lineSubtotal),
                    ]);

                    $product->decrement('stock', $stockOut);
                }

                // Trigger CashFlow sync
                $cashFlowService = new \App\Services\CashFlowService();
                $cashFlowService->syncFromSale($sale);

                // Log into sale_histories
                \App\Models\SaleHistory::create([
                    'sale_id' => $sale->id,
                    'invoice_number' => $sale->invoice_number,
                    'date' => $sale->date, // represents latest change date
                    'customer_id' => $sale->customer_id,
                    'salesman_id' => $sale->salesman_id,
                    'subtotal' => $sale->subtotal,
                    'discount' => $sale->discount,
                    'tax' => $sale->tax,
                    'total' => $sale->total,
                    'status' => $sale->status,
                    'notes' => $sale->notes,
                ]);
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
        $this->authorize('delete', $sale);
        $this->validateTeamAccess($sale);
        DB::transaction(function () use ($sale) {
            $sale->load('items');
            foreach ($sale->items as $existing) {
                $product = Product::where('code', $existing->product_code)->lockForUpdate()->first();
                if ($product) {
                    $product->increment('stock', (int) $existing->quantity + (int) ($existing->bonus ?? 0));
                }
            }
            $sale->delete();

            // Trigger CashFlow sync
            $cashFlowService = new CashFlowService();
            $cashFlowService->syncFromSale($sale);
        });

        return redirect()->route('sale.index')
            ->with('success', 'Penjualan berhasil dihapus');
    }

    private function validateTeamAccess(Sale $sale)
    {
        $user = auth()->user();
        if (in_array($user->role, ['sales', 'supervisor', 'manager'])) {
            $allowedIds = $this->getAllowedSalesmanIds($user);
            abort_unless(in_array($sale->salesman_id, $allowedIds), 403, 'Anda tidak memiliki hak akses untuk transaksi di luar wilayah kerja tim Anda.');
        }
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
}
