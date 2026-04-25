@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Rekap transaksi penjualan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Rekap Penjualan</h3>
        <a href="{{ route('sale.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2 rounded-lg transition">
            Lihat Transaksi
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">Invoice</th>
                    <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-5 py-3 text-left font-semibold">Customer</th>
                    <th class="px-5 py-3 text-left font-semibold">Salesman</th>
                    <th class="px-5 py-3 text-left font-semibold">Total</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $s)
                <tr class="table-row border-b border-slate-100">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $s->invoice_number }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ optional($s->date)->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">{{ $s->customer?->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $s->salesman?->name ?? '-' }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="badge {{ $s->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $s->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data penjualan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

