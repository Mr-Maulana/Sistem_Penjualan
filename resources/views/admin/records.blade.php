@extends('layouts.admin')

@section('title', 'Data Records')
@section('page-title', 'Data Records')
@section('page-subtitle', 'Ikhtisar statistik dan jumlah baris data pada sistem database')

@section('content')
<div class="space-y-6">
    <!-- Storage & DB Overview Stats Card -->
    <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm overflow-hidden relative">
        <div class="absolute right-0 top-0 w-36 h-36 bg-emerald-500/5 rounded-full blur-2xl translate-x-10 -translate-y-10"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center border border-emerald-100 shadow-sm">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 text-sm uppercase tracking-wide">Status Ukuran Database</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-widest">Penyimpanan dinamis MySQL</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">TOTAL STORAGE</span>
                <span class="px-4 py-2 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-black rounded-2xl shadow-sm">
                    {{ $dbSize }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Grid of Records -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $entities = [
                ['name' => 'Produk', 'count' => $counts['products'], 'icon' => 'package', 'color' => 'indigo', 'route' => 'product.index', 'desc' => 'Daftar katalog barang'],
                ['name' => 'Pelanggan / Customer', 'count' => $counts['customers'], 'icon' => 'users', 'color' => 'emerald', 'route' => 'customer.index', 'desc' => 'Daftar pembeli terdaftar'],
                ['name' => 'Salesman', 'count' => $counts['salesmen'], 'icon' => 'user-check', 'color' => 'blue', 'route' => 'salesman.index', 'desc' => 'Kader tenaga penjual'],
                ['name' => 'Supplier', 'count' => $counts['suppliers'], 'icon' => 'truck', 'color' => 'purple', 'route' => 'supplier.index', 'desc' => 'Distributor barang masuk'],
                ['name' => 'Penjualan', 'count' => $counts['sales'], 'icon' => 'shopping-cart', 'color' => 'amber', 'route' => 'sale.index', 'desc' => 'Transaksi jual beli'],
                ['name' => 'Arus Kas / Bank', 'count' => $counts['cash_flows'], 'icon' => 'landmark', 'color' => 'rose', 'route' => 'cash-flow.index', 'desc' => 'Buku kas & bank'],
                ['name' => 'Wilayah / Area', 'count' => $counts['areas'], 'icon' => 'map', 'color' => 'orange', 'route' => 'area.index', 'desc' => 'Pembagian area kerja'],
                ['name' => 'User & Role', 'count' => $counts['users'], 'icon' => 'shield', 'color' => 'slate', 'route' => 'user.index', 'desc' => 'Kredensial sistem login'],
            ];
        @endphp

        @foreach($entities as $entity)
            <div class="group relative flex flex-col bg-white rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="p-6 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-{{ $entity['color'] }}-50 group-hover:text-{{ $entity['color'] }}-600 flex items-center justify-center border border-slate-100 group-hover:border-{{ $entity['color'] }}-100 transition-all duration-300">
                        <i data-lucide="{{ $entity['icon'] }}" class="w-6 h-6"></i>
                    </div>

                    <h3 class="mt-4 font-black text-slate-800 text-sm uppercase tracking-tight truncate w-full px-2">{{ $entity['name'] }}</h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $entity['desc'] }}</p>

                    <div class="mt-4 px-4 py-1.5 bg-slate-50 border border-slate-100 rounded-xl group-hover:bg-{{ $entity['color'] }}-50 group-hover:border-{{ $entity['color'] }}-100 transition-all duration-300">
                        <span class="text-xs font-black text-slate-800 group-hover:text-{{ $entity['color'] }}-700">{{ $entity['count'] }} Baris Data</span>
                    </div>
                </div>

                <a href="{{ Route::has($entity['route']) ? route($entity['route']) : '#' }}" 
                   class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 mt-auto flex items-center justify-between group-hover:bg-{{ $entity['color'] }}-500 transition-all duration-300">
                    <span class="text-[9px] font-black text-slate-400 group-hover:text-white uppercase tracking-widest">Buka Modul</span>
                    <div class="w-7 h-7 rounded-lg bg-white text-slate-300 group-hover:text-slate-900 flex items-center justify-center transition-all shadow-sm">
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
