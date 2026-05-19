@extends('layouts.admin')

@section('title', 'Approval Mutasi')
@section('page-title', 'Persetujuan Mutasi Tim')
@section('page-subtitle', 'Kelola pengajuan pindah anggota sales antar supervisor')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 flex justify-between items-center border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Daftar Pengajuan</h3>
            <p class="text-xs text-slate-500 mt-1">Review dan ACC permohonan mutasi anggota sales</p>
        </div>
        <a href="{{ route('team.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
            Kembali ke Tim
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                    <th class="px-6 py-4 text-left font-semibold">Salesman</th>
                    <th class="px-6 py-4 text-left font-semibold">Supervisor Asal</th>
                    <th class="px-6 py-4 text-left font-semibold">Supervisor Tujuan</th>
                    <th class="px-6 py-4 text-left font-semibold">Alasan</th>
                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                    <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transfers as $transfer)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 text-slate-500 whitespace-nowrap">{{ $transfer->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $transfer->salesman->name }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $transfer->fromSupervisor->name ?? 'Belum Ada' }}</td>
                    <td class="px-6 py-4 font-semibold text-blue-600">{{ $transfer->toSupervisor->name ?? 'Keluar Dari Tim (Tanpa Atasan)' }}</td>
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $transfer->reason }}">
                        {{ $transfer->reason }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($transfer->status === 'pending')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 ring-1 ring-amber-600/20">Pending</span>
                        @elseif($transfer->status === 'approved')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">Approved</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700 ring-1 ring-red-600/20">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        @if($transfer->status === 'pending')
                            <form action="{{ route('team.process', $transfer) }}" method="POST" onsubmit="return confirm('Tolak mutasi ini?')">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Tolak</button>
                            </form>
                            <form action="{{ route('team.process', $transfer) }}" method="POST" onsubmit="return confirm('ACC mutasi ini?')">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors">ACC</button>
                            </form>
                        @else
                            <span class="text-xs text-slate-400">Diproses oleh: {{ $transfer->approvedBy->name ?? 'Sistem' }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-3 text-slate-300"></i>
                        <p>Belum ada pengajuan mutasi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
