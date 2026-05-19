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
        @can('create', App\Models\Salesman::class)
        <a href="{{ route('salesman.create') }}" class="bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Salesman
        </a>
        @endcan
    </div>

    <!-- Search & Filter -->
    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
        <form action="{{ route('salesman.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" 
                    placeholder="Cari kode, nama, atau area...">
            </div>
            <div class="w-full md:w-48">
                <select name="status" onchange="this.form.submit()" 
                    class="block w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="w-full md:w-48">
                <select name="level" onchange="this.form.submit()" 
                    class="block w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                    <option value="">Semua Level</option>
                    <option value="sales" {{ request('level') == 'sales' ? 'selected' : '' }}>Sales</option>
                    <option value="supervisor" {{ request('level') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="manager" {{ request('level') == 'manager' ? 'selected' : '' }}>Manager</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-slate-700 transition-all">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'level']))
                    <a href="{{ route('salesman.index') }}" class="bg-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 rounded-xl hover:bg-slate-300 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Salesman</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Level / Kota</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Atasan</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($salesmen as $salesman)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0 border-2 border-white shadow-sm ring-1 ring-slate-200">
                                @if($salesman->photo)
                                    <img src="{{ asset('storage/' . $salesman->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                        <i data-lucide="user" class="w-6 h-6"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-800 tracking-tight leading-none mb-1">{{ $salesman->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">ID: {{ $salesman->code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md {{ $salesman->level == 'manager' ? 'bg-purple-100 text-purple-700' : ($salesman->level == 'supervisor' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700') }} text-[9px] font-black uppercase tracking-widest mb-1 border {{ $salesman->level == 'manager' ? 'border-purple-200' : ($salesman->level == 'supervisor' ? 'border-blue-200' : 'border-orange-200') }}">
                            {{ $salesman->level }}
                        </span>
                        <div class="flex items-center gap-1.5 text-slate-500 text-[10px] font-bold uppercase tracking-tight">
                            <i data-lucide="map-pin" class="w-3 h-3 text-slate-300"></i>
                            @if($salesman->area_display)
                                {{ $salesman->area_display }}
                            @else
                                {{ $salesman->city }} • {{ $salesman->area }}
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                                <i data-lucide="phone" class="w-3 h-3 text-slate-400"></i> {{ $salesman->phone }}
                            </div>
                            <div class="flex items-center gap-2 text-[10px] font-medium text-slate-400 truncate max-w-[150px]">
                                <i data-lucide="mail" class="w-3 h-3"></i> {{ $salesman->email ?: '-' }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($salesman->supervisor)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-slate-700 truncate">{{ $salesman->supervisor->name }}</p>
                                    <p class="text-[9px] text-slate-400 uppercase font-black">{{ $salesman->supervisor->level }}</p>
                                </div>
                            </div>
                        @else
                            <span class="text-slate-300 italic text-[10px] font-bold uppercase tracking-widest">TOP LEVEL</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $salesman->status == 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $salesman->status == 'active' ? 'bg-emerald-500' : 'bg-red-500' }} animate-pulse"></span>
                            {{ $salesman->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('salesman.show', $salesman) }}" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all shadow-sm" title="Lihat Profil">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            @can('update', $salesman)
                            <a href="{{ route('salesman.edit', $salesman) }}" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-orange-500 hover:text-white transition-all shadow-sm" title="Edit Data">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            @endcan
                            @can('delete', $salesman)
                            <form action="{{ route('salesman.destroy', $salesman) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus salesman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-red-500 hover:text-white transition-all shadow-sm" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center justify-center space-y-4">
                            <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center">
                                <i data-lucide="users" class="w-10 h-10 text-slate-200"></i>
                            </div>
                            <div>
                                <p class="font-black text-slate-800 uppercase tracking-widest text-xs">Data Salesman Kosong</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Mulai tambahkan tenaga penjual baru ke sistem</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

