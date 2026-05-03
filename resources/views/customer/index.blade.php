@extends('layouts.admin')

@section('title', 'Customer')
@section('page-title', 'Customer')
@section('page-subtitle', 'Kelola data customer')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Data Customer</h3>
        <a href="{{ route('customer.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-1.5 transition">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Customer
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">ID</th>
                    <th class="px-5 py-3 text-left font-semibold">Nama</th>
                    <th class="px-5 py-3 text-left font-semibold">Alamat</th>
                    <th class="px-5 py-3 text-left font-semibold">Telepon</th>
                    <th class="px-5 py-3 text-left font-semibold">Salesman</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="table-row border-b border-slate-100">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $customer->code }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">{{ $customer->name }}</td>
                    <td class="px-5 py-3 text-slate-600 max-w-[260px] truncate" title="{{ $customer->address }}">{{ $customer->address }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $customer->phone }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $customer->salesman?->name ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="badge {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $customer->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 flex gap-1">
                        <a href="{{ route('customer.edit', $customer) }}" class="p-1.5 rounded hover:bg-slate-100 text-slate-500">
                            <i data-lucide="edit" style="width:15px;height:15px;"></i>
                        </a>
                        <form action="{{ route('customer.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada data customer</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

