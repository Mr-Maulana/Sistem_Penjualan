<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\CodeGenerator;

class ProductController extends Controller
{
    use CodeGenerator;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        
        $selectedSupplier = null;
        if ($request->filled('supplier_id')) {
            $selectedSupplier = Supplier::with(['products' => function($q) use ($request) {
                if ($request->filled('search')) {
                    $q->where('name', 'like', "%{$request->search}%");
                }
            }])->where('code', $request->supplier_id)->first();
        }

        $suppliersQuery = Supplier::withCount('products');
        if ($request->filled('search') && !$request->filled('supplier_id')) {
            $suppliersQuery->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('company_name', 'like', "%{$request->search}%");
            });
        }
        $suppliers = $suppliersQuery->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('product.index', [
            'suppliers' => $suppliers,
            'selectedSupplier' => $selectedSupplier,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $this->authorize('create', Product::class);
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        // We'll handle the autoCode generation via JS based on supplier prefix
        $autoCode = ''; 
        return view('product.form', compact('categories', 'suppliers', 'autoCode'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,code',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $supplier = Supplier::where('code', $request->supplier_id)->firstOrFail();
        
        // Find latest number
        $latestProduct = Product::where('supplier_code', $supplier->code)
            ->where('code', 'like', $supplier->product_code . '-%')
            ->orderBy('code', 'desc')
            ->first();

        $nextNum = 1;
        if ($latestProduct) {
            $parts = explode('-', $latestProduct->code);
            $lastPart = end($parts);
            if (is_numeric($lastPart)) {
                $nextNum = (int)$lastPart + 1;
            }
        }
        
        $code = $supplier->product_code . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        Product::create([
            'code' => $code,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'supplier_code' => $supplier->code,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('product.index', ['supplier_id' => $supplier->code])
            ->with('success', 'Produk berhasil ditambahkan ke katalog ' . $supplier->name . '.');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return view('product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('product.form', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,code',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Keep current code unless supplier changed
        $code = $product->code;
        if ($product->supplier_code != $request->supplier_id) {
            $supplier = Supplier::where('code', $request->supplier_id)->firstOrFail();
            $latestProduct = Product::where('supplier_code', $supplier->code)
                ->where('code', 'like', $supplier->product_code . '-%')
                ->orderBy('code', 'desc')
                ->first();

            $nextNum = 1;
            if ($latestProduct) {
                $parts = explode('-', $latestProduct->code);
                $lastPart = end($parts);
                if (is_numeric($lastPart)) {
                    $nextNum = (int)$lastPart + 1;
                }
            }
            $code = $supplier->product_code . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        }

        $product->update([
            'code' => $code,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'supplier_code' => $request->supplier_id,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('product.index', ['supplier_id' => $request->supplier_id])
            ->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();

        return redirect()->route('product.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
