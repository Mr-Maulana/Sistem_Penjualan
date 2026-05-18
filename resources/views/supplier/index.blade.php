@extends('layouts.admin')

@section('title', 'Master Supplier')
@section('page-title', 'Supplier')
@section('page-subtitle', 'Kelola data suplier dan rekanan perusahaan')

@section('content')
<div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col">
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Master Supplier</h2>
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-widest mt-1">Total {{ $suppliers->count() }} Pemasok Terdaftar</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative group">
                <form action="{{ route('supplier.index') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="block w-full md:w-64 pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all bg-white font-medium" 
                        placeholder="Cari supplier...">
                </form>
            </div>
            @if(auth()->user()?->role === 'admin')
            <a href="{{ route('supplier.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-4 h-4 text-white"></i> Tambah Supplier
            </a>
            @endif
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Profil Perusahaan</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identitas Produk</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi & Kontak</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($suppliers as $sup)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-50 flex items-center justify-center text-orange-600 font-bold text-xl border border-orange-100 shadow-sm group-hover:scale-110 transition-transform">
                                    {{ substr($sup->company_name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <div class="font-bold text-slate-700 text-base tracking-tight leading-tight">{{ $sup->name }}</div>
                                        <span class="px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded text-[8px] font-bold uppercase">{{ $sup->code }}</span>
                                    </div>
                                    <div class="text-[11px] font-semibold text-slate-400 uppercase mt-1 flex items-center gap-1.5">
                                        <i data-lucide="building" class="w-3 h-3 text-slate-300"></i>
                                        {{ $sup->company_name }}
                                    </div>
                                    <div class="text-[9px] font-semibold text-slate-300 mt-0.5 tracking-wider">NPWP: {{ $sup->npwp ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-semibold uppercase tracking-tighter border border-blue-100">
                                    <i data-lucide="package" class="w-3 h-3"></i>
                                    {{ $sup->product_type ?? 'UMUM' }}
                                </div>
                                <div class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest pl-1 mt-1.5">Prefix: <span class="text-orange-600 font-bold">{{ $sup->product_code ?? 'NO CODE' }}</span></div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-slate-700 font-semibold text-sm tracking-tight">
                                    <div class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                    </div>
                                    {{ strtoupper($sup->city) }}
                                </div>
                                <div class="flex items-center gap-2 text-slate-400 font-semibold text-[10px] tracking-wide">
                                    <div class="w-6 h-6 rounded-lg bg-emerald-50 flex items-center justify-center">
                                        <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-400"></i>
                                    </div>
                                    {{ $sup->phone }}
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full {{ $sup->status == 'active' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-400 border-slate-200' }} text-[9px] font-bold uppercase tracking-widest border shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sup->status == 'active' ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                    {{ $sup->status == 'active' ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('supplier.show', $sup) }}" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-blue-500 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-100 transition-all active:scale-90" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('product.index', ['supplier_id' => $sup->code]) }}" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-emerald-500 hover:border-emerald-100 hover:shadow-lg hover:shadow-emerald-100 transition-all active:scale-90" title="Lihat Katalog Produk">
                                    <i data-lucide="package" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('supplier.edit', $sup) }}" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-orange-500 hover:border-orange-100 hover:shadow-lg hover:shadow-orange-100 transition-all active:scale-90" title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('supplier.destroy', $sup) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data supplier ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-red-500 hover:border-red-100 hover:shadow-lg hover:shadow-red-100 transition-all active:scale-90" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center max-w-xs mx-auto">
                                <div class="w-20 h-20 rounded-[2.5rem] bg-slate-50 flex items-center justify-center mb-6 border-4 border-white shadow-xl shadow-slate-100">
                                    <i data-lucide="truck" class="w-10 h-10 text-slate-200"></i>
                                </div>
                                <h4 class="font-bold text-slate-700 text-lg tracking-tight">Database Kosong</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Belum ada mitra supplier yang terdaftar di sistem.</p>
                                <a href="{{ route('supplier.create') }}" class="mt-8 px-6 py-3 bg-orange-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 transition-all">Mulai Input Sekarang</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection