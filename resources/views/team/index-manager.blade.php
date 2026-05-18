@extends('layouts.admin')

@section('title', 'Manajemen Wilayah')
@section('page-title', 'Struktur Organisasi Wilayah')
@section('page-subtitle', 'Monitoring Supervisor dan Sales di bawah naungan Anda')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-3xl p-6 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider mb-1">Level Anda</p>
            <h3 class="text-2xl font-black mb-4">Manager (Provinsi)</h3>
            <div class="flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-indigo-200"></i>
                <span class="text-sm font-semibold">{{ $me->area_display ?: $me->area }}</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Supervisor</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $supervisors->count() }}</h3>
            <p class="text-[10px] text-slate-500 mt-2 flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3 h-3"></i>
                Aktif mengelola kota
            </p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Sales</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $supervisors->sum(fn($s) => $s->subordinates->count()) }}</h3>
            <p class="text-[10px] text-slate-500 mt-2 flex items-center gap-1">
                <i data-lucide="users" class="w-3 h-3"></i>
                Aktif di lapangan
            </p>
        </div>
    </div>

    <!-- Hierarchy Tree View -->
    <div class="space-y-6">
        @forelse($supervisors as $supervisor)
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <!-- Supervisor Header -->
            <div class="px-8 py-5 bg-slate-50/80 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 text-lg leading-tight">{{ $supervisor->name }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-bold bg-blue-600 text-white px-2 py-0.5 rounded uppercase tracking-tighter">Supervisor</span>
                            <span class="text-[11px] text-slate-500 font-medium flex items-center gap-1">
                                <i data-lucide="map" class="w-3 h-3"></i> {{ $supervisor->area_display ?: $supervisor->area }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tim Sales</p>
                        <p class="font-black text-slate-700">{{ $supervisor->subordinates->count() }} Orang</p>
                    </div>
                    <div class="text-right border-l border-slate-200 pl-6">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target Tim</p>
                        <p class="font-black text-emerald-600 font-mono text-sm">Rp {{ number_format($supervisor->subordinates->sum('target'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Sales List (Subordinates) -->
            <div class="p-6">
                @if($supervisor->subordinates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($supervisor->subordinates as $sales)
                    <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/30 hover:bg-white hover:border-blue-200 hover:shadow-md hover:shadow-blue-500/5 transition-all duration-300 group">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-white text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 flex items-center justify-center border border-slate-100 group-hover:border-blue-100 transition-colors shadow-sm">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black text-slate-800 truncate">{{ $sales->name }}</p>
                                <p class="text-[10px] text-slate-500 font-medium uppercase tracking-tight">{{ $sales->area_display ?: ($sales->city . ' - ' . $sales->area) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] border-t border-slate-100 pt-3">
                            <div class="text-slate-500">
                                <p class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Target</p>
                                <p class="font-bold">Rp {{ number_format($sales->target, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Kontak</p>
                                <p class="font-mono">{{ $sales->phone }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-8 text-slate-400 opacity-60">
                    <i data-lucide="users" class="w-12 h-12 mb-3 stroke-[1.5]"></i>
                    <p class="text-sm font-semibold italic">Belum ada tim sales di bawah supervisor ini.</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-slate-200">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <i data-lucide="shield-alert" class="w-10 h-10"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-800">Belum ada Supervisor</h4>
            <p class="text-slate-500 max-w-sm mx-auto mt-2 italic">Data supervisor di bawah naungan manager di area ini belum terdata.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
