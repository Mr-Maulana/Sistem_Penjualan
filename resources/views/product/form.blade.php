@extends('layouts.admin')

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
@section('page-title', 'Produk')
@section('page-subtitle', isset($product) ? 'Edit data produk' : 'Tambah data produk')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}</h3>
        </div>
        <form method="POST" action="{{ isset($product) ? route('product.update', $product) : route('product.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Kode Produk</label>
                    <input name="code" value="{{ old('code', $product->code ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('code') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('stock') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Nama</label>
                <input name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('name') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Kategori</label>
                    <select name="category_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ (string)old('category_id', $product->category_id ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Distributor</label>
                    <select name="distributor_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($distributors as $d)
                            <option value="{{ $d->id }}" {{ (string)old('distributor_id', $product->distributor_id ?? '') === (string)$d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('distributor_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('price') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-2 pt-2">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('product.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

