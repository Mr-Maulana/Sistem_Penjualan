@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'System Activity')
@section('page-subtitle', 'Log aktivitas dan audit operasional sistem secara real-time')

@section('content')
<div class="space-y-6">
    <!-- Header Summary Card -->
    <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm overflow-hidden relative">
        <div class="absolute right-0 top-0 w-36 h-36 bg-blue-500/5 rounded-full blur-2xl translate-x-10 -translate-y-10"></div>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center border border-blue-100 shadow-sm">
                <i data-lucide="activity" class="w-5 h-5 animate-pulse"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-800 text-sm uppercase tracking-wide">Pemantauan Aktivitas Sistem</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-widest">Menampilkan {{ count($activities) }} log operasi terbaru</p>
            </div>
        </div>
    </div>

    <!-- Timeline Activity Logs -->
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-8">
        <div class="relative border-l-2 border-slate-100 pl-8 space-y-8">
            @forelse($activities as $act)
                <div class="relative group">
                    <!-- Icon Indicator -->
                    <div class="absolute -left-[45px] top-0.5 w-7 h-7 rounded-xl border {{ $act['color'] }} flex items-center justify-center shadow-sm z-10 transition-transform group-hover:scale-110">
                        <i data-lucide="{{ $act['icon'] }}" class="w-3.5 h-3.5"></i>
                    </div>

                    <!-- Log Details -->
                    <div class="space-y-1">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="text-xs font-black text-slate-800 flex items-center gap-2">
                                <span class="text-slate-500 font-bold uppercase">{{ $act['user'] }}</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span class="text-indigo-600 uppercase tracking-tight">{{ $act['action'] }}</span>
                            </span>
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                                {{ $act['time']->diffForHumans() }} ({{ $act['time']->format('d M Y, H:i') }})
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed mt-1">
                            {{ $act['detail'] }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-400">
                    <div class="flex flex-col items-center">
                        <i data-lucide="history" class="w-12 h-12 mb-4 opacity-20 animate-spin-slow"></i>
                        <p class="font-bold">Belum ada aktivitas yang tercatat</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
