@extends('layouts.app')

@section('title', 'Salesman')
@section('page-title', 'Salesman')
@section('page-subtitle', 'Kelola data salesman')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Data Salesman</h3>
        <a href="{{ route('salesman.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-1.5 transition">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Salesman
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">ID</th>
                    <th class="px-5 py-3 text-left font-semibold">Nama</th>
                    <th class="px-5 py-3 text-left font-semibold">Area</th>
                    <th class="px-5 py-3 text-left font-semibold">Telepon</th>
                    <th class="px-5 py-3 text-left font-semibold">Target</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesmen as $salesman)
                <tr class="table-row border-b border-slate-100">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $salesman->code }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">{{ $salesman->name }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $salesman->area }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $salesman->phone }}</td>
                    <td class="px-5 py-3 text-slate-600">Rp {{ number_format($salesman->target, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="badge {{ $salesman->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $salesman->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 flex gap-1">
                        <a href="{{ route('salesman.edit', $salesman) }}" class="p-1.5 rounded hover:bg-slate-100 text-slate-500">
                            <i data-lucide="edit" style="width:15px;height:15px;"></i>
                        </a>
                        <form action="{{ route('salesman.destroy', $salesman) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded hover:bg-red-50 text-red-400">
                                <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada data salesman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

