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
            <a href="{{ route('report.sales.export.csv') }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all border border-emerald-100 shadow-sm">
                <i data-lucide="file-spreadsheet" style="width:16px;height:16px;"></i> Export CSV
            </a>
            <a href="{{ route('report.sales.export.pdf') }}" class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-md">
                <i data-lucide="file-text" style="width:16px;height:16px;"></i> Unduh PDF
            </a>
        </div>
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

