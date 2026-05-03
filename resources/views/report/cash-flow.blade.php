@extends('layouts.admin')

@section('title', 'Laporan Kas / Bank')
@section('page-title', 'Laporan Kas / Bank')
@section('page-subtitle', 'Rekap arus kas & bank')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Rekap Kas / Bank</h3>
        <a href="{{ route('cash-flow.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2 rounded-lg transition">
            Lihat Mutasi
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">ID</th>
                    <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-5 py-3 text-left font-semibold">Jenis</th>
                    <th class="px-5 py-3 text-left font-semibold">Keterangan</th>
                    <th class="px-5 py-3 text-left font-semibold">Jumlah</th>
                    <th class="px-5 py-3 text-left font-semibold">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cashFlows as $cf)
                <tr class="table-row border-b border-slate-100">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $cf->code }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ optional($cf->date)->format('d/m/Y') }}</td>
                    <td class="px-5 py-3">
                        <span class="badge {{ $cf->type === 'in' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $cf->type === 'in' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-slate-600">{{ $cf->description }}</td>
                    <td class="px-5 py-3 font-semibold {{ $cf->type === 'in' ? 'text-emerald-700' : 'text-red-600' }}">Rp {{ number_format($cf->amount, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($cf->balance, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data kas/bank</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

