@extends('layouts.admin')

@section('title', 'Salesman')
@section('page-title', 'Salesman')
@section('page-subtitle', 'Kelola data salesman')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Data Salesman</h3>
            <p class="text-xs text-slate-500 mt-1">Daftar semua tenaga penjual dalam sistem</p>
        </div>
        <a href="{{ route('salesman.create') }}" class="bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Salesman
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 text-left font-semibold">ID</th>
                    <th class="px-6 py-4 text-left font-semibold">Nama</th>
                    <th class="px-6 py-4 text-left font-semibold">Area</th>
                    <th class="px-6 py-4 text-left font-semibold">Telepon</th>
                    <th class="px-6 py-4 text-left font-semibold">Target</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                    <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($salesmen as $salesman)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $salesman->code }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($salesman->name, 0, 1)) }}
                            </div>
                            <div class="font-semibold text-slate-800">{{ $salesman->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $salesman->area }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $salesman->phone }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 border border-slate-200/60 font-semibold text-xs">
                            Rp {{ number_format($salesman->target, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $salesman->status == 'active' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $salesman->status == 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            {{ $salesman->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('salesman.show', $salesman) }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition-colors" title="Lihat Detail">
                            <i data-lucide="eye" style="width:16px;height:16px;"></i>
                        </a>
                        <a href="{{ route('salesman.edit', $salesman) }}" class="p-2 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition-colors" title="Edit">
                            <i data-lucide="pencil" style="width:16px;height:16px;"></i>
                        </a>
                        <form action="{{ route('salesman.destroy', $salesman) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus salesman ini?')">
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
                                <i data-lucide="user-check" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada data salesman</p>
                            <p class="text-xs text-slate-400 mt-1">Mulai dengan menambahkan salesman baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

