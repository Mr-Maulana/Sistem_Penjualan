@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Produk')
@section('page-subtitle', 'Kelola data produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Data Produk</h3>
        <a href="{{ route('product.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-1.5 transition">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Produk
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">Kode</th>
                    <th class="px-5 py-3 text-left font-semibold">Nama</th>
                    <th class="px-5 py-3 text-left font-semibold">Kategori</th>
                    <th class="px-5 py-3 text-left font-semibold">Distributor</th>
                    <th class="px-5 py-3 text-left font-semibold">Harga</th>
                    <th class="px-5 py-3 text-left font-semibold">Stok</th>
                    <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="table-row border-b border-slate-100">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $product->code }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">{{ $product->name }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $product->category?->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $product->distributor?->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-slate-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $product->stock }}</td>
                    <td class="px-5 py-3 flex gap-1">
                        <a href="{{ route('product.edit', $product) }}" class="p-1.5 rounded hover:bg-slate-100 text-slate-500">
                            <i data-lucide="edit" style="width:15px;height:15px;"></i>
                        </a>
                        <form action="{{ route('product.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded hover:bg-red-50 text-red-400">
                                <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada data produk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

