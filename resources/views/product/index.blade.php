@extends('layouts.admin')

@section('title', 'Katalog Produk')
@section('page-title', 'Katalog Produk')
@section('page-subtitle', $selectedSupplier ? 'Mengelola katalog produk dari ' . $selectedSupplier->name : 'Pilih supplier untuk mengelola produk')

@section('content')
<div class="space-y-8">
    @if($selectedSupplier)
    <!-- Product Table View (Like Price Menu) -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden">
        <!-- Header -->
        <div class="px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-100 bg-white">
            <div class="flex items-center gap-6">
                <a href="{{ route('product.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all active:scale-90 shadow-sm" title="Kembali ke Supplier">
                    <i data-lucide="arrow-left" class="w-6 h-6"></i>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h3 class="font-semibold text-xl text-slate-500 tracking-tight uppercase">{{ $selectedSupplier->name }}</h3>
                        <span class="px-3 py-1 rounded-lg bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest border border-amber-100 shadow-sm">{{ $selectedSupplier->product_code }}</span>
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1.5 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        {{ $selectedSupplier->company_name }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @can('create', App\Models\Product::class)
                <a href="{{ route('product.create', ['supplier_id' => $selectedSupplier->code]) }}" class="h-14 px-8 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl flex items-center gap-3 transition-all active:scale-95 shadow-xl shadow-blue-100 font-black uppercase tracking-widest text-[10px]">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Produk Baru
                </a>
                @endcan
            </div>
        </div>

        <!-- Search & Filter Area -->
        <div class="px-10 py-6 bg-slate-50/50 border-b border-slate-100">
            <form action="{{ route('product.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <input type="hidden" name="supplier_id" value="{{ $selectedSupplier->code }}">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="block w-full pl-14 pr-6 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all shadow-sm" 
                        placeholder="Cari nama produk di katalog ini...">
                </div>
                <button type="submit" class="h-14 px-8 bg-white border border-slate-200 text-slate-700 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-slate-50 transition-all shadow-sm">
                    Cari Produk
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('product.index', ['supplier_id' => $selectedSupplier->code]) }}" class="h-14 px-8 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center hover:bg-slate-200 transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Body -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest font-black border-b border-slate-100">
                        <th class="px-10 py-6 text-left">Kode Produk</th>
                        <th class="px-10 py-6 text-left">Nama Produk</th>
                        <th class="px-10 py-6 text-left">Kategori</th>
                        <th class="px-10 py-6 text-center">Stok Unit</th>
                        <th class="px-10 py-6 text-right">Harga Jual</th>
                        <th class="px-10 py-6 text-center">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($selectedSupplier->products as $product)
                    <tr class="hover:bg-blue-50/20 transition-all group">
                        <td class="px-10 py-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100">
                                {{ $product->code }}
                            </span>
                        </td>
                        <td class="px-10 py-6">
                            <div class="font-medium text-slate-600 text-sm tracking-normal">{{ $product->name }}</div>
                        </td>
                        <td class="px-10 py-6">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-50 text-slate-400 text-[10px] font-medium uppercase tracking-widest border border-slate-100">
                                {{ $product->category->name ?? 'UMUM' }}
                            </div>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $product->stock > 10 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }} border text-[10px] font-black uppercase tracking-widest">
                                {{ $product->stock }} UNIT
                            </div>
                        </td>
                        <td class="px-10 py-6 text-right font-semibold text-slate-500 text-sm tracking-tight">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('product.show', $product) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-emerald-600 hover:bg-white hover:shadow-md border border-transparent hover:border-emerald-100 transition-all active:scale-90" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('product.edit', $product) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-white hover:shadow-md border border-transparent hover:border-blue-100 transition-all active:scale-90" title="Edit Produk">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('product.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari katalog?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-white hover:shadow-md border border-transparent hover:border-red-100 transition-all active:scale-90" title="Hapus Produk">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 border border-slate-100">
                                    <i data-lucide="package-search" class="w-10 h-10 text-slate-200"></i>
                                </div>
                                <h4 class="font-black text-slate-800 text-lg tracking-tight uppercase">Belum Ada Katalog</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Daftarkan produk pertama untuk supplier ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @else
    <!-- Search Bar for Suppliers -->
    <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-slate-200/60 mb-6">
        <form action="{{ route('product.index') }}" method="GET" class="flex items-center gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 transition-all" 
                    placeholder="Cari nama supplier atau perusahaan...">
            </div>
            <button type="submit" class="h-10 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold uppercase tracking-widest text-[10px] transition-all shadow-sm">
                Filter
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('product.index') }}" class="h-10 px-5 bg-slate-100 text-slate-500 rounded-xl font-bold uppercase tracking-widest text-[10px] flex items-center hover:bg-slate-200 transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @php
        $colors = [
            ['bg' => 'from-blue-500 to-indigo-600', 'soft' => 'bg-blue-50', 'text' => 'text-blue-600'],
            ['bg' => 'from-emerald-500 to-teal-600', 'soft' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
            ['bg' => 'from-amber-500 to-orange-600', 'soft' => 'bg-amber-50', 'text' => 'text-amber-600'],
            ['bg' => 'from-rose-500 to-pink-600', 'soft' => 'bg-rose-50', 'text' => 'text-rose-600'],
            ['bg' => 'from-violet-500 to-purple-600', 'soft' => 'bg-violet-50', 'text' => 'text-violet-600'],
            ['bg' => 'from-sky-500 to-blue-600', 'soft' => 'bg-sky-50', 'text' => 'text-sky-600'],
        ];
    @endphp

    <!-- Suppliers Grid Selection -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($suppliers as $index => $supplier)
        @php $color = $colors[$index % count($colors)]; @endphp
        <a href="{{ route('product.index', ['supplier_id' => $supplier->code]) }}" 
           class="group relative flex flex-col bg-white rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-slate-200 transition-all duration-500 overflow-hidden active:scale-95">
            
            <!-- Subtle Color Top Bar -->
            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r {{ $color['bg'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

            <div class="p-6 flex flex-col items-center text-center relative">
                <!-- Compact Premium Logo Background -->
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $color['bg'] }} shadow-lg shadow-blue-100 flex items-center justify-center text-2xl font-black text-white group-hover:scale-110 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    {{ substr($supplier->company_name ?? $supplier->name, 0, 1) }}
                </div>

                <h3 class="mt-4 font-black text-slate-800 text-base tracking-tight uppercase group-hover:text-blue-600 transition-colors truncate w-full px-2">{{ $supplier->name }}</h3>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1 truncate w-full px-4">{{ $supplier->company_name }}</p>
                
                <div class="mt-5 flex items-center gap-2">
                    <div class="px-3 py-1 rounded-lg {{ $color['soft'] }} {{ $color['text'] }} text-[9px] font-black uppercase tracking-widest border border-transparent group-hover:border-current transition-all">
                        {{ $supplier->products_count }} ITEM
                    </div>
                    <div class="px-3 py-1 rounded-lg bg-slate-50 text-slate-400 text-[9px] font-black uppercase tracking-widest border border-slate-100">
                        {{ $supplier->product_code }}
                    </div>
                </div>
            </div>

            <!-- Compact Footer -->
            <div class="px-6 py-4 bg-slate-50/50 mt-auto border-t border-slate-100 flex items-center justify-between group-hover:bg-gradient-to-r {{ $color['bg'] }} transition-all duration-300">
                <span class="text-[9px] font-black text-slate-400 group-hover:text-white uppercase tracking-widest">Buka Katalog</span>
                <div class="w-8 h-8 rounded-lg bg-white text-slate-300 flex items-center justify-center group-hover:text-slate-900 transition-all shadow-sm">
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-32 text-center bg-white rounded-[3rem] border-4 border-dashed border-slate-100">
            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-8">
                    <i data-lucide="truck" class="w-12 h-12 text-slate-200"></i>
                </div>
                <h4 class="font-black text-slate-800 text-2xl tracking-tight uppercase">Supplier Kosong</h4>
                <p class="text-sm text-slate-400 font-bold mt-4 px-10 leading-relaxed uppercase tracking-widest">Silakan daftarkan supplier di menu Master Data terlebih dahulu.</p>
            </div>
        </div>
        @endforelse
    </div>
    @endif
</div>
@endsection
