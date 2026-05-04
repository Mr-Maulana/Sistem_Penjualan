@extends('layouts.admin')

@section('title', 'Penjualan')
@section('page-title', 'Penjualan')
@section('page-subtitle', 'Kelola transaksi penjualan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Transaksi Penjualan</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola dan monitor semua riwayat transaksi penjualan</p>
        </div>
        <a href="{{ route('sale.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Penjualan
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 text-left font-semibold">No. Invoice</th>
                    <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                    <th class="px-6 py-4 text-left font-semibold">Customer</th>
                    <th class="px-6 py-4 text-left font-semibold">Salesman</th>
                    <th class="px-6 py-4 text-right font-semibold">Total Akhir</th>
                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                    <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sales as $sale)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-blue-600 tracking-tight">{{ $sale->invoice_number }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ optional($sale->date)->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $sale->customer?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $sale->salesman?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-right font-bold text-slate-800">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @php($status = $sale->status)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $status == 'paid' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : ($status == 'partial' ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20') }}">
                            <span class="w-1 h-1 rounded-full {{ $status == 'paid' ? 'bg-emerald-500' : ($status == 'partial' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                            {{ $status == 'paid' ? 'Lunas' : ($status == 'partial' ? 'Sebagian' : 'Belum Lunas') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-1">
                        <a href="{{ route('sale.show', $sale) }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition-colors" title="Lihat Detail">
                            <i data-lucide="eye" style="width:16px;height:16px;"></i>
                        </a>
                        <a href="{{ route('sale.print', $sale) }}" target="_blank" class="p-2 rounded-lg hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 transition-colors" title="Cetak Invoice">
                            <i data-lucide="printer" style="width:16px;height:16px;"></i>
                        </a>
                        <a href="{{ route('sale.edit', $sale) }}" class="p-2 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition-colors" title="Edit">
                            <i data-lucide="pencil" style="width:16px;height:16px;"></i>
                        </a>
                        <form action="{{ route('sale.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
                                <i data-lucide="receipt" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada transaksi penjualan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

