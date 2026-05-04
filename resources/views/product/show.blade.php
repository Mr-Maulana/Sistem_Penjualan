@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')
@section('page-subtitle', 'Informasi detail produk / barang')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden max-w-4xl mx-auto">
    <div class="px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl ring-1 ring-blue-500/20">
                <i data-lucide="package" style="width:24px;height:24px;"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">{{ $product->name }}</h3>
                <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-0.5">
                    Kategori: {{ $product->category->name ?? 'Tanpa Kategori' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('product.edit', $product) }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all shadow-sm">
                <i data-lucide="pencil" style="width:16px;height:16px;"></i> Edit
            </a>
            <a href="{{ route('product.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode Produk</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="barcode" style="width:18px;height:18px;" class="text-blue-500"></i>
                    <p class="font-mono text-slate-800 font-semibold text-lg">{{ $product->code }}</p>
                </div>
            </div>
            
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Distributor (Supplier)</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="truck" style="width:18px;height:18px;" class="text-slate-400"></i>
                    <p class="text-slate-800 font-semibold text-base">{{ $product->distributor->name ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100">
                <p class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-2">Harga Dasar</p>
                <div class="flex items-center gap-2.5">
                    <i data-lucide="tag" style="width:24px;height:24px;" class="text-blue-500 mt-0.5"></i>
                    <p class="text-slate-800 text-2xl font-extrabold tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="{{ $product->stock > 10 ? 'bg-emerald-50/50 border-emerald-100' : ($product->stock > 0 ? 'bg-amber-50/50 border-amber-100' : 'bg-red-50/50 border-red-100') }} rounded-2xl p-6 border">
                <p class="text-xs font-bold {{ $product->stock > 10 ? 'text-emerald-500' : ($product->stock > 0 ? 'text-amber-500' : 'text-red-500') }} uppercase tracking-wider mb-2">Stok Saat Ini</p>
                <div class="flex items-center gap-2.5">
                    <i data-lucide="boxes" style="width:24px;height:24px;" class="{{ $product->stock > 10 ? 'text-emerald-500' : ($product->stock > 0 ? 'text-amber-500' : 'text-red-500') }} mt-0.5"></i>
                    <p class="text-slate-800 text-2xl font-extrabold tracking-tight">{{ $product->stock }} <span class="text-base font-semibold text-slate-500 ml-1">unit</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
