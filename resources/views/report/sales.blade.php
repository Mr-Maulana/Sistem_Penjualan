@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Rekap transaksi penjualan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Rekap Penjualan</h3>
        <div class="flex gap-2">
            <a href="{{ route('report.sales.export.csv') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-1.5">
                <i data-lucide="file-spreadsheet" style="width:16px;height:16px;"></i> CSV
            </a>
            <a href="{{ route('report.sales.export.pdf') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-1.5">
                <i data-lucide="file-text" style="width:16px;height:16px;"></i> PDF
            </a>
            <a href="{{ route('sale.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2 rounded-lg transition">
                Lihat Transaksi
            </a>
        </div>
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
                        @php($isPaid = $s->status === 'paid')
                        @php($isPartial = $s->status === 'partial')
                        <span class="badge {{ $isPaid ? 'bg-emerald-100 text-emerald-700' : ($isPartial ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ $isPaid ? 'Lunas' : ($isPartial ? 'Sebagian' : 'Belum Lunas') }}
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

