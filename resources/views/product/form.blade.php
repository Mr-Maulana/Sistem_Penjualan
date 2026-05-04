@extends('layouts.admin')

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
@section('page-title', 'Produk')
@section('page-subtitle', isset($product) ? 'Edit data produk' : 'Tambah data produk')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 max-w-4xl mx-auto overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white">
        <h3 class="font-bold text-slate-800 text-lg">{{ isset($product) ? 'Edit Data Produk' : 'Tambah Produk Baru' }}</h3>
        <p class="text-xs text-slate-500 mt-1">Lengkapi informasi produk di bawah ini untuk manajemen stok yang lebih baik.</p>
    </div>
    <form method="POST" action="{{ isset($product) ? route('product.update', $product) : route('product.store') }}" class="p-8">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Produk</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="barcode" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="text" name="code" value="{{ old('code', $product->code ?? $autoCode ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm bg-slate-100 text-slate-500 cursor-not-allowed font-bold"
                           readonly required placeholder="Otomatis">
                </div>
                @error('code') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                <p class="text-[10px] text-slate-400 mt-1 font-medium italic">* Kode diisi otomatis oleh sistem</p>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Stok Awal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="boxes" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           required min="0">
                </div>
                @error('stock') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Produk</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="package" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input name="name" value="{{ old('name', $product->name ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           required placeholder="Masukkan nama barang">
                </div>
                @error('name') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Kategori</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="tag" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <select name="category_id" class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50 appearance-none">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ (string)old('category_id', $product->category_id ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('category_id') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Distributor</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="truck" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <select name="distributor_id" class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50 appearance-none">
                        <option value="">-- Pilih Distributor --</option>
                        @foreach($distributors as $d)
                            <option value="{{ $d->id }}" {{ (string)old('distributor_id', $product->distributor_id ?? '') === (string)$d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('distributor_id') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Harga Jual Dasar (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <span class="text-slate-400 font-bold text-sm">Rp</span>
                    </div>
                    <input type="number" name="price" value="{{ old('price', $product->price ?? 0) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50 font-mono font-semibold"
                           required placeholder="Contoh: 50000">
                </div>
                @error('price') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                <i data-lucide="save" style="width:16px;height:16px;"></i> Simpan Produk
            </button>
            <a href="{{ route('product.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-600 font-bold py-2.5 px-6 rounded-xl text-sm transition-all flex items-center gap-2">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

