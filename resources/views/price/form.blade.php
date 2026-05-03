@extends('layouts.admin')

@section('title', isset($price) ? 'Edit Harga' : 'Tambah Harga')
@section('page-title', 'Harga')
@section('page-subtitle', isset($price) ? 'Edit harga produk' : 'Tambah harga produk')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">{{ isset($price) ? 'Edit Harga' : 'Tambah Harga' }}</h3>
        </div>
        <form method="POST" action="{{ isset($price) ? route('price.update', $price) : route('price.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($price))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Produk</label>
                    <select name="product_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ (string)old('product_id', $price->product_id ?? '') === (string)$p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Grup Customer</label>
                    <input name="customer_group" value="{{ old('customer_group', $price->customer_group ?? '') }}" placeholder="Contoh: Grosir / Retail / VIP" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('customer_group') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Harga Besar (Rp)</label>
                    <input type="number" name="price_large" value="{{ old('price_large', $price->price_large ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('price_large') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Harga Kecil (Rp)</label>
                    <input type="number" name="price_small" value="{{ old('price_small', $price->price_small ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('price_small') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Diskon Default (Rp)</label>
                    <input type="number" name="discount" value="{{ old('discount', $price->discount ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('discount') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Pajak Default (Rp)</label>
                    <input type="number" name="tax" value="{{ old('tax', $price->tax ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('tax') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tgl Efektif</label>
                    <input type="date" name="effective_date" value="{{ old('effective_date', isset($price) ? optional($price->effective_date)->format('Y-m-d') : '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('effective_date') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('price.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

