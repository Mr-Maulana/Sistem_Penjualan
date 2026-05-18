@extends('layouts.admin')

@php
    $backSupplierCode = $product->supplier_code ?? request('supplier_id');
@endphp

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
@section('page-title', isset($product) ? 'Edit Produk' : 'Tambah Produk Baru')
@section('page-subtitle', isset($product) ? 'Edit data produk — ' . ($product->supplier->name ?? '') : 'Tambah produk ke katalog supplier')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Smart Back Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ $backSupplierCode ? route('product.index', ['supplier_id' => $backSupplierCode]) : route('product.index') }}" 
           class="flex items-center gap-2 text-slate-400 hover:text-blue-600 transition-colors font-black text-[10px] uppercase tracking-widest group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            @if($backSupplierCode)
                Kembali ke Katalog {{ $product->supplier->name ?? 'Supplier' }}
            @else
                Kembali ke Daftar Produk
            @endif
        </a>

        @if(isset($product))
            <span class="px-3 py-1 rounded-lg bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest border border-amber-100 flex items-center gap-1.5">
                <i data-lucide="pencil" class="w-3 h-3"></i>
                Mode Edit
            </span>
        @else
            <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-1.5">
                <i data-lucide="plus" class="w-3 h-3"></i>
                Produk Baru
            </span>
        @endif
    </div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-10 py-8 border-b border-slate-100 bg-white">
        <h3 class="font-black text-slate-800 text-2xl tracking-tight">{{ isset($product) ? 'Edit Data Produk' : 'Tambah Produk Baru' }}</h3>
        <p class="text-xs text-slate-500 mt-1.5">Lengkapi informasi produk di bawah ini untuk manajemen katalog yang lebih baik.</p>
    </div>
    <form method="POST" action="{{ isset($product) ? route('product.update', $product) : route('product.store') }}" class="p-8">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">Nama Produk</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <i data-lucide="package" style="width:18px;height:18px;"></i>
                    </div>
                    <input name="name" value="{{ old('name', $product->name ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all bg-white font-black text-slate-800 placeholder:text-slate-300"
                           required placeholder="Masukkan nama barang">
                </div>
                @error('name') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 mb-2 uppercase tracking-wide">Kategori</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <i data-lucide="tag" style="width:16px;height:16px;"></i>
                    </div>
                    <select name="category_id" class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all bg-white appearance-none shadow-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ (string)old('category_id', $product->category_id ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('category_id') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 mb-2 uppercase tracking-wide flex items-center gap-2">
                    Supplier Utama
                    @if(isset($product) || request('supplier_id'))
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-orange-50 text-orange-500 border border-orange-100 text-[9px] font-black">
                            <i data-lucide="lock" class="w-2.5 h-2.5"></i> TERKUNCI
                        </span>
                    @endif
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <i data-lucide="truck" style="width:16px;height:16px;"></i>
                    </div>
                    @php 
                        $isLocked = isset($product) || request('supplier_id'); 
                        $currentSupplierId = old('supplier_id', $product->supplier_code ?? request('supplier_id', ''));
                    @endphp
                    <select name="supplier_id" id="supplier_id" class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all bg-white appearance-none shadow-sm {{ $isLocked ? 'bg-slate-50 cursor-not-allowed text-slate-500 font-bold' : '' }}" required {{ $isLocked ? 'disabled' : '' }}>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->code }}" data-prefix="{{ $s->product_code }}" {{ (string)$currentSupplierId === (string)$s->code ? 'selected' : '' }}>
                                {{ strtoupper($s->name) }} [{{ $s->product_code }}]
                            </option>
                        @endforeach
                    </select>
                    @if($isLocked)
                        <input type="hidden" name="supplier_id" value="{{ $currentSupplierId }}">
                    @endif
                </div>
                @error('supplier_id') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">Kode Produk</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="barcode" style="width:16px;height:16px;"></i>
                    </div>
                    <input type="text" name="code" id="product_code_input" value="{{ old('code', $product->code ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm bg-slate-50 text-slate-500 font-black cursor-not-allowed"
                           readonly required placeholder="Otomatis mengikuti supplier...">
                </div>
                @error('code') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                <p class="text-[10px] text-slate-400 mt-1 font-bold italic">* Kode dikelola otomatis oleh sistem berdasarkan supplier</p>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">Stok Awal</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <i data-lucide="boxes" style="width:16px;height:16px;"></i>
                    </div>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all bg-white font-black text-slate-800"
                           required min="0">
                </div>
                @error('stock') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">Harga Jual Dasar (Rp)</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <span class="font-bold text-sm">Rp</span>
                    </div>
                    <input type="number" name="price" value="{{ old('price', $product->price ?? 0) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all bg-white font-mono font-black text-slate-800"
                           required placeholder="Contoh: 50000">
                </div>
                @error('price') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-black py-3 px-8 rounded-2xl text-sm transition-all shadow-lg shadow-blue-500/20 hover:-translate-y-1 flex items-center gap-2 active:scale-95">
                <i data-lucide="save" style="width:18px;height:18px;"></i> SIMPAN PRODUK
            </button>
            <a href="{{ $backSupplierCode ? route('product.index', ['supplier_id' => $backSupplierCode]) : route('product.index') }}" 
               class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-black py-3 px-8 rounded-2xl text-sm transition-all flex items-center gap-2 active:scale-95">
                <i data-lucide="x" style="width:16px;height:16px;"></i> BATAL
            </a>
        </div>
    </form>
</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const supplierSelect = document.getElementById('supplier_id');
    const codeInput = document.getElementById('product_code_input');

    // Only run if supplier is not locked (disabled) and code not already set
    if (!supplierSelect || supplierSelect.disabled) return;

    function generateCode() {
        const selected = supplierSelect.options[supplierSelect.selectedIndex];
        const prefix = selected ? selected.getAttribute('data-prefix') : null;

        if (!prefix) {
            codeInput.value = '';
            codeInput.placeholder = 'Pilih supplier terlebih dahulu...';
            return;
        }

        codeInput.placeholder = 'Menghitung kode...';
        codeInput.value = '';

        // Fetch the next available code from the server using the supplier code (value)
        fetch(`/api/supplier/${encodeURIComponent(supplierSelect.value)}/info`)
            .then(response => response.json())
            .then(data => {
                codeInput.value = data.code ?? (prefix + '-001');
            })
            .catch(() => {
                // Fallback: show prefix pattern if request fails
                codeInput.value = prefix + '-???';
                codeInput.placeholder = 'Kode akan digenerate saat simpan';
            });
    }

    supplierSelect.addEventListener('change', generateCode);

    // Trigger on load if already selected and code is empty
    if (supplierSelect.value && !codeInput.value) {
        generateCode();
    }
});
</script>
@endpush
@endsection
