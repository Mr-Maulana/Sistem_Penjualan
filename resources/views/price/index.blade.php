@extends('layouts.admin')

@section('title', 'Harga')
@section('page-title', 'Harga')
@section('page-subtitle', 'Kelola harga per grup customer')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Master Harga</h3>
            <p class="text-xs text-slate-500 mt-1">Atur penetapan harga produk berdasarkan grup customer</p>
        </div>
        <a href="{{ route('price.create') }}" class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Harga
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 text-left font-semibold">Produk</th>
                    <th class="px-6 py-4 text-left font-semibold">Grup Customer</th>
                    <th class="px-6 py-4 text-left font-semibold">Harga Besar</th>
                    <th class="px-6 py-4 text-left font-semibold">Harga Kecil</th>
                    <th class="px-6 py-4 text-left font-semibold">Diskon</th>
                    <th class="px-6 py-4 text-left font-semibold">Pajak</th>
                    <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($prices as $p)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-800">{{ $p->product?->name ?? '-' }}</div>
                        <div class="font-mono text-[10px] text-slate-400 mt-0.5 uppercase tracking-tighter">{{ $p->product?->code ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-slate-50 text-slate-600 text-xs font-medium border border-slate-200/60">
                            {{ $p->customer_group ?: 'Umum (Default)' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-700">Rp {{ number_format($p->price_large ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-bold text-slate-700">Rp {{ number_format($p->price_small ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="text-emerald-600 font-bold">{{ $p->discount }}%</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-amber-600 font-bold">{{ $p->tax }}%</span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('price.show', $p) }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition-colors" title="Lihat Detail">
                            <i data-lucide="eye" style="width:16px;height:16px;"></i>
                        </a>
                        <a href="{{ route('price.edit', $p) }}" class="p-2 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition-colors" title="Edit">
                            <i data-lucide="pencil" style="width:16px;height:16px;"></i>
                        </a>
                        <form action="{{ route('price.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus harga ini?')">
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
                                <i data-lucide="tag" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="font-medium text-slate-500">Belum ada data harga</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

