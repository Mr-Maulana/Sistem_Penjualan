@extends('layouts.admin')

@section('title', 'Kas / Bank')
@section('page-title', 'Kas / Bank')
@section('page-subtitle', 'Kelola arus kas & bank')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Mutasi Kas / Bank</h3>
            <p class="text-xs text-slate-500 mt-1">Lacak dan pantau semua aliran dana masuk dan keluar</p>
        </div>
        <a href="{{ route('cash-flow.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Transaksi
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 text-left font-semibold">Kode</th>
                    <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                    <th class="px-6 py-4 text-center font-semibold">Jenis</th>
                    <th class="px-6 py-4 text-left font-semibold">Keterangan</th>
                    <th class="px-6 py-4 text-right font-semibold">Jumlah</th>
                    <th class="px-6 py-4 text-right font-semibold">Saldo Akhir</th>
                    <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($cashFlows as $cf)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-blue-600 tracking-tight">{{ $cf->code }}</td>
                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">{{ optional($cf->date)->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $cf->type === 'in' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                            <span class="w-1 h-1 rounded-full {{ $cf->type === 'in' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            {{ $cf->type === 'in' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 min-w-[200px]">
                        <div class="line-clamp-1 group-hover:line-clamp-none transition-all duration-300">
                            {{ $cf->description }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right font-bold {{ $cf->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $cf->type === 'in' ? '+' : '-' }} Rp {{ number_format($cf->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-right font-black text-slate-800 font-mono tracking-tighter">
                        Rp {{ number_format($cf->balance, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-1">
                        <a href="{{ route('cash-flow.show', $cf) }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition-colors" title="Lihat Detail">
                            <i data-lucide="eye" style="width:16px;height:16px;"></i>
                        </a>
                        <a href="{{ route('cash-flow.edit', $cf) }}" class="p-2 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition-colors" title="Edit">
                            <i data-lucide="pencil" style="width:16px;height:16px;"></i>
                        </a>
                        <form action="{{ route('cash-flow.destroy', $cf) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-colors" title="Hapus">
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
                                <i data-lucide="wallet" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada mutasi kas/bank</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

