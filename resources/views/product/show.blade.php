@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', 'Katalog Produk')
@section('page-subtitle', 'Detail informasi produk ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('product.index', ['supplier_id' => $product->supplier_code]) }}" class="flex items-center gap-2 text-slate-400 hover:text-blue-600 transition-colors font-black text-[10px] uppercase tracking-widest group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Katalog {{ $product->supplier->name }}
        </a>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-10">
            <div class="flex flex-col md:flex-row gap-10">
                <!-- Product Illustration/Icon -->
                <div class="w-full md:w-1/3">
                    <div class="aspect-square rounded-[2.5rem] bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-2xl shadow-blue-100">
                        <i data-lucide="package" class="w-24 h-24"></i>
                    </div>
                    <div class="mt-6 flex flex-col gap-3">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kode Produk</div>
                            <div class="text-xl font-black text-slate-800 tracking-tight mt-1">{{ $product->code }}</div>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kategori</div>
                            <div class="text-base font-black text-slate-800 tracking-tight mt-1">{{ $product->category->name ?? 'UMUM' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex-1 space-y-8">
                    <div>
                        <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100">
                            Official Product
                        </span>
                        <h2 class="text-4xl font-black text-slate-800 tracking-tighter mt-4 leading-tight">{{ $product->name }}</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Stok Saat Ini</div>
                            <div class="flex items-center gap-2">
                                <div class="text-3xl font-black text-slate-800">{{ $product->stock }}</div>
                                <div class="text-xs font-bold text-slate-400 uppercase">Unit</div>
                            </div>
                            <div class="mt-2">
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full" style="width: {{ min(($product->stock / 100) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Harga Jual Dasar</div>
                            <div class="flex items-baseline gap-1">
                                <div class="text-xs font-bold text-slate-400">Rp</div>
                                <div class="text-3xl font-black text-slate-800 tracking-tighter">{{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                            <p class="text-[9px] text-emerald-600 font-black uppercase tracking-widest mt-2 flex items-center gap-1">
                                <i data-lucide="trending-up" class="w-3 h-3"></i>
                                Harga Stabil
                            </p>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-blue-600 font-black">
                                {{ substr($product->supplier->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Supplier Utama</div>
                                <div class="text-sm font-black text-slate-800 uppercase">{{ $product->supplier->name }}</div>
                            </div>
                        </div>
                        <a href="{{ route('product.index', ['supplier_id' => $product->supplier_code]) }}" class="text-[9px] font-black text-blue-600 uppercase tracking-widest hover:underline">
                            Lihat Katalog Lainnya
                        </a>
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <a href="{{ route('sale.index', ['search' => $product->code]) }}" class="flex-1 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center gap-3 font-black uppercase tracking-widest text-[10px] hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 active:scale-95">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Riwayat Penjualan Produk
                        </a>
                        @can('update', $product)
                        <a href="{{ route('product.edit', $product) }}" class="flex-1 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center gap-3 font-black uppercase tracking-widest text-[10px] hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 active:scale-95">
                            <i data-lucide="pencil" class="w-4 h-4"></i> Edit Informasi Produk
                        </a>
                        @endcan
                        @can('delete', $product)
                        <form action="{{ route('product.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center hover:bg-red-100 transition-all border border-red-100 active:scale-95">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
