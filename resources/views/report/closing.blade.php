@extends('layouts.admin')

@section('title', 'Closing / Assessment')
@section('page-title', 'Closing / Assessment')
@section('page-subtitle', 'Evaluasi & penutupan periode')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Penjualan</div>
        <div class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">{{ $paidCount + $unpaidCount }} transaksi</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Sudah Lunas</div>
        <div class="text-2xl font-extrabold text-emerald-600">Rp {{ number_format($paidSales, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">{{ $paidCount }} transaksi</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Belum Lunas</div>
        <div class="text-2xl font-extrabold text-red-500">Rp {{ number_format($unpaidSales, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">{{ $unpaidCount }} transaksi</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <h3 class="font-bold text-slate-800 mb-4 text-sm">Ringkasan Kas / Bank</h3>
        <div class="space-y-3">
            <div class="flex justify-between"><span class="text-slate-500">Kas Masuk</span><span class="font-bold text-emerald-600">Rp {{ number_format($cashIn, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kas Keluar</span><span class="font-bold text-red-500">Rp {{ number_format($cashOut, 0, ',', '.') }}</span></div>
            <hr class="border-slate-200">
            <div class="flex justify-between"><span class="font-semibold text-slate-700">Saldo Akhir</span><span class="font-extrabold text-slate-800 text-lg">Rp {{ number_format($endingBalance, 0, ',', '.') }}</span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <h3 class="font-bold text-slate-800 mb-4 text-sm">Assessment Salesman</h3>
        <div class="space-y-3">
            @forelse($salesmanAssessment as $s)
                @php
                    $grade = $s['grade'];
                    $gradeColor = $grade === 'A' ? 'bg-emerald-100 text-emerald-700'
                        : ($grade === 'B' ? 'bg-blue-100 text-blue-700'
                        : ($grade === 'C' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600'));
                @endphp
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-700 text-sm">{{ $s['name'] }}</div>
                        <div class="text-xs text-slate-400">Pencapaian: {{ $s['percentage'] }}% · Rp {{ number_format($s['achievement'], 0, ',', '.') }} / Rp {{ number_format($s['target'], 0, ',', '.') }}</div>
                    </div>
                    <span class="badge {{ $gradeColor }} text-base font-bold px-3">{{ $grade }}</span>
                </div>
            @empty
                <p class="text-slate-400 text-sm">Belum ada data salesman</p>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
    <h3 class="font-bold text-slate-800 mb-4 text-sm">Invoice Belum Lunas</h3>
    @if($unpaidInvoices->count() === 0)
        <p class="text-slate-400 text-sm">Semua invoice sudah lunas</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                        <th class="px-4 py-2 text-left font-semibold">Invoice</th>
                        <th class="px-4 py-2 text-left font-semibold">Customer</th>
                        <th class="px-4 py-2 text-left font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unpaidInvoices as $p)
                    <tr class="table-row border-b border-slate-100">
                        <td class="px-4 py-2 font-mono text-xs text-slate-500">{{ $p->invoice_number }}</td>
                        <td class="px-4 py-2 text-slate-700">{{ $p->customer?->name ?? '-' }}</td>
                        <td class="px-4 py-2 font-bold text-slate-800">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

