@extends('layouts.admin')

@section('title', 'Laporan Kas / Bank')
@section('page-title', 'Laporan Kas / Bank')
@section('page-subtitle', 'Rekap arus kas & bank')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Rekap Kas / Bank</h3>
            <p class="text-xs text-slate-500 mt-1">Laporan historis seluruh pergerakan dana kas dan bank</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('report.cash-flow.export.pdf') }}" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-md shadow-red-100 hover:-translate-y-0.5 active:scale-95">
                <i data-lucide="file-text" style="width:16px;height:16px;"></i> PDF
            </a>
            <a href="{{ route('cash-flow.index') }}" class="bg-slate-50 hover:bg-slate-100 text-slate-600 text-sm font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all border border-slate-200 shadow-sm">
                <i data-lucide="eye" style="width:16px;height:16px;"></i> Detail Mutasi
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-[10px] uppercase tracking-widest">
                    <th class="px-6 py-4 text-left font-bold">Kode</th>
                    <th class="px-6 py-4 text-left font-bold">Tanggal</th>
                    <th class="px-6 py-4 text-center font-bold">Jenis</th>
                    <th class="px-6 py-4 text-left font-bold">Keterangan</th>
                    <th class="px-6 py-4 text-right font-bold">Nominal</th>
                    <th class="px-6 py-4 text-right font-bold">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($cashFlows as $cf)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500">{{ $cf->code }}</td>
                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap font-medium">{{ optional($cf->date)->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $cf->type === 'in' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                            <span class="w-1 h-1 rounded-full {{ $cf->type === 'in' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            {{ $cf->type === 'in' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium max-w-[200px] truncate">{{ $cf->description }}</td>
                    <td class="px-6 py-4 text-right font-bold {{ $cf->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $cf->type === 'in' ? '+' : '-' }} Rp {{ number_format($cf->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-right font-black text-slate-800 font-mono tracking-tighter">
                        Rp {{ number_format($cf->balance, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                                <i data-lucide="wallet" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada data kas/bank</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

