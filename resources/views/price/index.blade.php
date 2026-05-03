@extends('layouts.admin')

@section('title', 'Harga')
@section('page-title', 'Harga')
@section('page-subtitle', 'Kelola harga per grup customer')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Master Harga</h3>
        <a href="{{ route('price.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-1.5 transition">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Harga
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">Produk</th>
                    <th class="px-5 py-3 text-left font-semibold">Grup Customer</th>
                    <th class="px-5 py-3 text-left font-semibold">Harga Besar</th>
                    <th class="px-5 py-3 text-left font-semibold">Harga Kecil</th>
                    <th class="px-5 py-3 text-left font-semibold">Diskon</th>
                    <th class="px-5 py-3 text-left font-semibold">Pajak</th>
                    <th class="px-5 py-3 text-left font-semibold">Efektif</th>
                    <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prices as $p)
                <tr class="table-row border-b border-slate-100">
                    <td class="px-5 py-3">
                        <div class="font-semibold text-slate-800">{{ $p->product?->name ?? '-' }}</div>
                        <div class="font-mono text-xs text-slate-500">{{ $p->product?->code ?? '' }}</div>
                    </td>
                    <td class="px-5 py-3 text-slate-600">{{ $p->customer_group ?: '-' }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($p->price_large ?? 0, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($p->price_small ?? 0, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-slate-600">Rp {{ number_format($p->discount ?? 0, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-slate-600">Rp {{ number_format($p->tax ?? 0, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ optional($p->effective_date)->format('d/m/Y') ?: '-' }}</td>
                    <td class="px-5 py-3 flex gap-1">
                        <a href="{{ route('price.edit', $p) }}" class="p-1.5 rounded hover:bg-slate-100 text-slate-500">
                            <i data-lucide="edit" style="width:15px;height:15px;"></i>
                        </a>
                        <form action="{{ route('price.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    <td colspan="8" class="px-5 py-8 text-center text-slate-400">Belum ada data harga</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

