@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data penjualan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="text-xs text-slate-500 font-semibold">Total Penjualan</div>
        <div class="text-xl font-extrabold text-slate-800 mt-1">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">{{ number_format($totalTransactions ?? 0) }} transaksi</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="text-xs text-slate-500 font-semibold">Lunas</div>
        <div class="text-xl font-extrabold text-emerald-700 mt-1">Rp {{ number_format($paidSales ?? 0, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">Status paid</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="text-xs text-slate-500 font-semibold">Belum Lunas</div>
        <div class="text-xl font-extrabold text-amber-700 mt-1">Rp {{ number_format($unpaidSales ?? 0, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">Status unpaid/partial</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="text-xs text-slate-500 font-semibold">Saldo Kas/Bank</div>
        <div class="text-xl font-extrabold text-slate-800 mt-1">Rp {{ number_format($currentBalance ?? 0, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">In: Rp {{ number_format($totalCashIn ?? 0, 0, ',', '.') }} | Out: Rp {{ number_format($totalCashOut ?? 0, 0, ',', '.') }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Transaksi Terbaru</h3>
            <a href="{{ route('sale.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                        <th class="px-5 py-3 text-left font-semibold">Invoice</th>
                        <th class="px-5 py-3 text-left font-semibold">Customer</th>
                        <th class="px-5 py-3 text-left font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $t)
                        <tr class="border-b border-slate-100">
                            <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $t->invoice_number }}</td>
                            <td class="px-5 py-3 font-semibold text-slate-800">{{ $t->customer?->name ?? '-' }}</td>
                            <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($t->total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-slate-400">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Stok Hampir Habis</h3>
            <a href="{{ route('product.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">Kelola produk</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                        <th class="px-5 py-3 text-left font-semibold">Produk</th>
                        <th class="px-5 py-3 text-left font-semibold">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $p)
                        <tr class="border-b border-slate-100">
                            <td class="px-5 py-3">
                                <div class="font-semibold text-slate-800">{{ $p->name }}</div>
                                <div class="font-mono text-xs text-slate-500">{{ $p->code }}</div>
                            </td>
                            <td class="px-5 py-3 font-semibold {{ ($p->stock ?? 0) <= 5 ? 'text-red-600' : 'text-amber-700' }}">{{ $p->stock ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-5 py-8 text-center text-slate-400">Tidak ada stok rendah</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

