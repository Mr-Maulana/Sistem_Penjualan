@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Produk')
@section('page-subtitle', 'Kelola data produk')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Data Produk</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola semua data barang dan produk</p>
        </div>
        <a href="{{ route('product.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Produk
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
        <form action="{{ route('product.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" 
                    placeholder="Cari kode atau nama produk...">
            </div>
            <div class="w-full md:w-48">
                <select name="category_id" onchange="this.form.submit()" 
                    class="block w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-48">
                <select name="distributor_id" onchange="this.form.submit()" 
                    class="block w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    <option value="">Semua Distributor</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id }}" {{ request('distributor_id') == $distributor->id ? 'selected' : '' }}>{{ $distributor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-slate-700 transition-all">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'distributor_id']))
                    <a href="{{ route('product.index') }}" class="bg-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 rounded-xl hover:bg-slate-300 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 text-left font-semibold">Kode</th>
                    <th class="px-6 py-4 text-left font-semibold">Nama Produk</th>
                    <th class="px-6 py-4 text-left font-semibold">Kategori</th>
                    <th class="px-6 py-4 text-left font-semibold">Distributor</th>
                    <th class="px-6 py-4 text-left font-semibold">Harga</th>
                    <th class="px-6 py-4 text-left font-semibold">Stok</th>
                    <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $product->code }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                <i data-lucide="package" style="width:16px;height:16px;"></i>
                            </div>
                            <div class="font-semibold text-slate-800">{{ $product->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->category)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-medium">
                                <i data-lucide="tag" style="width:12px;height:12px;"></i> {{ $product->category->name }}
                            </span>
                        @else
                            <span class="text-slate-400 italic text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $product->distributor?->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-slate-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2 py-1 rounded-full text-xs font-bold {{ $product->stock > 10 ? 'bg-emerald-50 text-emerald-600' : ($product->stock > 0 ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('product.show', $product) }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition-colors" title="Lihat Detail">
                            <i data-lucide="eye" style="width:16px;height:16px;"></i>
                        </a>
                        <a href="{{ route('product.edit', $product) }}" class="p-2 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition-colors" title="Edit">
                            <i data-lucide="pencil" style="width:16px;height:16px;"></i>
                        </a>
                        <form action="{{ route('product.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg hover:bg-red-50 text-slate-500 hover:text-red-500 transition-colors" title="Hapus">
                                <i data-lucide="trash-2" style="width:16px;height:16px;"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                                <i data-lucide="package" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada data produk</p>
                            <p class="text-xs text-slate-400 mt-1">Mulai dengan menambahkan produk baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

