@extends('layouts.admin')

@section('title', 'Closing / Assessment')
@section('page-title', 'Closing / Assessment')
@section('page-subtitle', 'Evaluasi & penutupan periode')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors"></div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i data-lucide="line-chart" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Penjualan</div>
            <div class="text-2xl font-black text-slate-800 tracking-tight mt-auto">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">{{ $paidCount + $unpaidCount }} TRANSAKSI TERCATAT</div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Sudah Lunas</div>
            <div class="text-2xl font-black text-emerald-600 tracking-tight mt-auto">Rp {{ number_format($paidSales, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-emerald-500/70 mt-1 uppercase tracking-tighter">{{ $paidCount }} TRANSAKSI SELESAI</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-500/5 rounded-full blur-2xl group-hover:bg-red-500/10 transition-colors"></div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center mb-4">
                <i data-lucide="alert-circle" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Belum Lunas</div>
            <div class="text-2xl font-black text-red-500 tracking-tight mt-auto">Rp {{ number_format($unpaidSales, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-red-400 mt-1 uppercase tracking-tighter">{{ $unpaidCount }} TRANSAKSI PENDING</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i data-lucide="wallet" style="width:18px;height:18px;"></i>
            </div>
            <h3 class="font-bold text-slate-800">Ringkasan Kas / Bank</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-medium flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Kas Masuk
                </span>
                <span class="font-bold text-emerald-600">Rp {{ number_format($cashIn, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-medium flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Kas Keluar
                </span>
                <span class="font-bold text-red-600">Rp {{ number_format($cashOut, 0, ',', '.') }}</span>
            </div>
            <div class="pt-4 border-t border-slate-100 flex justify-between items-end">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Saldo Akhir Berjalan</p>
                    <span class="font-extrabold text-slate-800 text-2xl tracking-tighter">Rp {{ number_format($endingBalance, 0, ',', '.') }}</span>
                </div>
                <div class="px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-[10px] font-bold text-slate-500 uppercase">UPDATE REALTIME</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="award" style="width:18px;height:18px;"></i>
            </div>
            <h3 class="font-bold text-slate-800">Assessment Salesman</h3>
        </div>
        <div class="p-6">
            <div class="space-y-5">
                @forelse($salesmanAssessment as $s)
                    @php
                        $grade = $s['grade'];
                        $gradeColor = $grade === 'A' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'
                            : ($grade === 'B' ? 'bg-blue-50 text-blue-700 ring-blue-600/20'
                            : ($grade === 'C' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 'bg-red-50 text-red-600 ring-red-600/20'));
                    @endphp
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <div class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    {{ $s['name'] }}
                                    @if($grade === 'A') <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-500"></i> @endif
                                </div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wide"> Achievement: Rp {{ number_format($s['achievement'], 0, ',', '.') }}</div>
                            </div>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl ring-1 font-black text-lg {{ $gradeColor }}">{{ $grade }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000 {{ $grade === 'A' ? 'bg-emerald-500' : ($grade === 'B' ? 'bg-blue-500' : ($grade === 'C' ? 'bg-amber-500' : 'bg-red-500')) }}" 
                                 style="width: {{ $s['percentage'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-4">
                        <i data-lucide="users" class="w-8 h-8 text-slate-300 mb-2"></i>
                        <p class="text-slate-400 text-sm font-medium">Belum ada data salesman</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                <i data-lucide="file-warning" style="width:18px;height:18px;"></i>
            </div>
            <h3 class="font-bold text-slate-800">Invoice Belum Lunas</h3>
        </div>
        <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider">{{ $unpaidInvoices->count() }} PENDING</span>
    </div>
    @if($unpaidInvoices->count() === 0)
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check" class="w-8 h-8 text-emerald-500"></i>
            </div>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs tracking-tighter">SEMUA INVOICE SUDAH LUNAS</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-[10px] uppercase tracking-widest">
                        <th class="px-6 py-4 text-left font-bold">Nomor Invoice</th>
                        <th class="px-6 py-4 text-left font-bold">Customer</th>
                        <th class="px-6 py-4 text-right font-bold">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($unpaidInvoices as $p)
                    <tr class="hover:bg-red-50/30 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500">{{ $p->invoice_number }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $p->customer?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-right font-black text-red-600">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

