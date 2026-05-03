@extends('layouts.admin')

@section('title', 'Penjualan')
@section('page-title', 'Penjualan')
@section('page-subtitle', 'Kelola transaksi penjualan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Transaksi Penjualan</h3>
        <a href="{{ route('sale.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-1.5 transition">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Penjualan
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">No. Invoice</th>
                    <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-5 py-3 text-left font-semibold">Customer</th>
                    <th class="px-5 py-3 text-left font-semibold">Salesman</th>
                    <th class="px-5 py-3 text-left font-semibold">Total</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr class="table-row border-b border-slate-100">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $sale->invoice_number }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ optional($sale->date)->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">{{ $sale->customer?->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $sale->salesman?->name ?? '-' }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        @php($isPaid = $sale->status === 'paid')
                        @php($isPartial = $sale->status === 'partial')
                        <span class="badge {{ $isPaid ? 'bg-emerald-100 text-emerald-700' : ($isPartial ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ $isPaid ? 'Lunas' : ($isPartial ? 'Sebagian' : 'Belum Lunas') }}
                        </span>
                    </td>
                    <td class="px-5 py-3 flex gap-1">
                        <a href="{{ route('sale.print', $sale) }}" class="p-1.5 rounded hover:bg-slate-100 text-slate-500" title="Cetak">
                            <i data-lucide="printer" style="width:15px;height:15px;"></i>
                        </a>
                        <a href="{{ route('sale.edit', $sale) }}" class="p-1.5 rounded hover:bg-slate-100 text-slate-500">
                            <i data-lucide="edit" style="width:15px;height:15px;"></i>
                        </a>
                        <form action="{{ route('sale.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada transaksi penjualan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

