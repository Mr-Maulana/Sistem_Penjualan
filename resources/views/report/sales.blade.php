@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Rekap transaksi penjualan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Rekap Penjualan</h3>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh transaksi penjualan yang terekam dalam sistem</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('report.sales.export.csv', request()->query()) }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all border border-emerald-100 shadow-sm">
                <i data-lucide="file-spreadsheet" style="width:16px;height:16px;"></i> Export CSV
            </a>
            <a href="{{ route('report.sales.export.pdf', request()->query()) }}" class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-md">
                <i data-lucide="file-text" style="width:16px;height:16px;"></i> Unduh PDF
            </a>
        </div>
    </div>
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/30">
        <form method="GET" action="{{ route('report.sales') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
            <div class="xl:col-span-2">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Cari Invoice / Customer</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: INV atau nama customer" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Supplier</label>
                <select name="supplier_code" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->code }}" @selected(request('supplier_code') == $supplier->code)>
                            {{ $supplier->name }}{{ $supplier->company_name ? ' - ' . $supplier->company_name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Kategori Produk</label>
                <select name="category_id" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Status</label>
                <select name="status" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    <option value="">Semua Status</option>
                    <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
                    <option value="partial" @selected(request('status') === 'partial')>Sebagian</option>
                    <option value="unpaid" @selected(request('status') === 'unpaid')>Belum Lunas</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Customer</label>
                <select name="customer_id" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    <option value="">Semua Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @selected((string) request('customer_id') === (string) $customer->id)>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Salesman</label>
                @if(auth()->user()->role === 'sales' && $salesmen->count() > 0)
                    <input type="hidden" name="salesman_id" value="{{ $salesmen->first()->id }}">
                    <input type="text" readonly value="{{ $salesmen->first()->name }}" class="w-full h-10 rounded-xl border border-slate-200 bg-slate-100 px-3 text-sm text-slate-500 cursor-not-allowed">
                @else
                    <select name="salesman_id" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">Semua Salesman</option>
                        @foreach($salesmen as $salesman)
                            <option value="{{ $salesman->id }}" @selected((string) request('salesman_id') === (string) $salesman->id)>{{ $salesman->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
            </div>
            <div class="xl:col-span-4 flex flex-wrap items-center gap-2 pt-1">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 h-10 rounded-xl flex items-center gap-2 transition-all">
                    <i data-lucide="filter" style="width:14px;height:14px;"></i> Terapkan Filter
                </button>
                <a href="{{ route('report.sales') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold px-4 h-10 rounded-xl flex items-center gap-2 transition-all">
                    <i data-lucide="refresh-ccw" style="width:14px;height:14px;"></i> Reset
                </a>
                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Total Data: {{ $sales->count() }}</span>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-[10px] uppercase tracking-widest">
                    <th class="px-6 py-4 text-left font-bold">Nomor Invoice</th>
                    <th class="px-6 py-4 text-left font-bold">Tanggal</th>
                    <th class="px-6 py-4 text-left font-bold">Customer</th>
                    <th class="px-6 py-4 text-left font-bold">Salesman</th>
                    <th class="px-6 py-4 text-right font-bold">Total Transaksi</th>
                    <th class="px-6 py-4 text-center font-bold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sales as $s)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-blue-600 tracking-tight">{{ $s->invoice_number }}</td>
                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">{{ optional($s->date)->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $s->customer?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-500 text-xs font-medium">{{ $s->salesman?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-right font-black text-slate-900 tracking-tight">
                        Rp {{ number_format($s->total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php($isPaid = $s->status === 'paid')
                        @php($isPartial = $s->status === 'partial')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $isPaid ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : ($isPartial ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-600/20' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20') }}">
                            <span class="w-1 h-1 rounded-full {{ $isPaid ? 'bg-emerald-500' : ($isPartial ? 'bg-sky-500' : 'bg-amber-500') }}"></span>
                            {{ $isPaid ? 'Lunas' : ($isPartial ? 'Sebagian' : 'Belum Lunas') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                                <i data-lucide="shopping-cart" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada data penjualan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

