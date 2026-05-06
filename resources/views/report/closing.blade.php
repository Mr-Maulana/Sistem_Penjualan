@extends('layouts.admin')

@section('title', 'Closing / Assessment')
@section('page-title', 'Closing / Assessment')
@section('page-subtitle', 'Evaluasi & penutupan periode')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 flex items-center justify-between mb-8">
    <div>
        <h3 class="font-bold text-slate-800 text-lg">Ringkasan Closing</h3>
        <p class="text-xs text-slate-500 mt-1">Laporan evaluasi performa dan ringkasan keuangan</p>
    </div>
    <a href="{{ route('report.closing.export.pdf') }}" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-md shadow-red-100 hover:-translate-y-0.5 active:scale-95">
        <i data-lucide="file-text" style="width:18px;height:18px;"></i> Download PDF
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group animate-slide-up stagger-1">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors"></div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i data-lucide="line-chart" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Penjualan</div>
            <div class="text-2xl font-black text-slate-800 tracking-tight mt-auto">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">{{ $paidCount + $unpaidCount }} TRANSAKSI</div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group animate-slide-up stagger-2">
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

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group animate-slide-up stagger-3">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:bg-rose-500/10 transition-colors"></div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                <i data-lucide="alert-circle" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Belum Lunas</div>
            <div class="text-2xl font-black text-rose-500 tracking-tight mt-auto">Rp {{ number_format($unpaidSales, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-rose-400 mt-1 uppercase tracking-tighter">{{ $unpaidCount }} INVOICE PENDING</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group animate-slide-up stagger-4">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-colors"></div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                <i data-lucide="wallet" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Saldo Kas Akhir</div>
            <div class="text-2xl font-black text-slate-800 tracking-tight mt-auto">Rp {{ number_format($endingBalance, 0, ',', '.') }}</div>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-tighter">IN: {{ number_format($cashIn, 0, ',', '.') }}</span>
                <span class="text-[9px] font-bold text-rose-500 uppercase tracking-tighter">OUT: {{ number_format($cashOut, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-1 gap-8 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden animate-slide-up">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="award" style="width:18px;height:18px;"></i>
                </div>
                <h3 class="font-bold text-slate-800">Assessment Salesman</h3>
            </div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">PERFORMA BERDASARKAN TARGET</div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                @forelse($salesmanAssessment as $s)
                    @php
                        $grade = $s['grade'];
                        $gradeColor = $grade === 'A' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'
                            : ($grade === 'B' ? 'bg-blue-50 text-blue-700 ring-blue-600/20'
                            : ($grade === 'C' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 'bg-rose-50 text-rose-600 ring-rose-600/20'));
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
                            <div class="h-full rounded-full transition-all duration-1000 {{ $grade === 'A' ? 'bg-emerald-500' : ($grade === 'B' ? 'bg-blue-500' : ($grade === 'C' ? 'bg-amber-500' : 'bg-rose-500')) }}" 
                                 style="width: {{ $s['percentage'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-4 col-span-2">
                        <i data-lucide="users" class="w-8 h-8 text-slate-300 mb-2"></i>
                        <p class="text-slate-400 text-sm font-medium">Belum ada data salesman</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden animate-slide-up stagger-2">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-sm">
                <i data-lucide="file-warning" style="width:20px;height:20px;"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Invoice Belum Lunas</h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Daftar piutang yang perlu ditindaklanjuti</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-lg bg-rose-100 text-rose-700 text-xs font-black uppercase tracking-wider">{{ $unpaidInvoices->count() }} PENDING</span>
        </div>
    </div>
    @if($unpaidInvoices->count() === 0)
        <div class="p-16 text-center">
            <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-xl shadow-emerald-100">
                <i data-lucide="check" class="w-10 h-10 text-emerald-500"></i>
            </div>
            <h4 class="text-slate-800 font-black text-lg">Luar Biasa!</h4>
            <p class="text-slate-500 font-medium text-sm mt-1">Semua invoice telah terbayar lunas.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                        <th class="px-8 py-5 text-left font-black">Nomor Invoice</th>
                        <th class="px-8 py-5 text-left font-black">Customer</th>
                        <th class="px-8 py-5 text-right font-black">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($unpaidInvoices as $p)
                    <tr class="hover:bg-slate-50/80 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-rose-400"></div>
                                <span class="font-mono text-xs font-bold text-slate-600 group-hover:text-rose-600 transition-colors">{{ $p->invoice_number }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 font-bold text-slate-700 group-hover:text-slate-900">{{ $p->customer?->name ?? '-' }}</td>
                        <td class="px-8 py-5 text-right font-black text-rose-600 bg-rose-50/20">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

