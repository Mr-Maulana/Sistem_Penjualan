@extends('layouts.admin')

@section('title', 'Health Status')
@section('page-title', 'Health Status')
@section('page-subtitle', 'Analisis dan diagnostik kesehatan server serta lingkungan aplikasi')

@section('content')
<div class="space-y-6">
    <!-- Server Status Indicator Banner -->
    <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm overflow-hidden relative">
        <div class="absolute right-0 top-0 w-36 h-36 bg-emerald-500/5 rounded-full blur-2xl translate-x-10 -translate-y-10"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center border border-emerald-100 shadow-sm relative">
                    <i data-lucide="heart" class="w-5 h-5 animate-pulse"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 text-sm uppercase tracking-wide">Status Sistem Operasional</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-widest">Semua layanan berjalan lancar</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">DATABASE STATUS</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-black rounded-xl">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span>
                    {{ $health['db_connection'] }}
                </span>
            </div>
        </div>
    </div>

    <!-- Health Grid Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Software Environment Card -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <i data-lucide="cpu" class="w-4 h-4 text-slate-400"></i>
                <h4 class="font-black text-slate-800 text-xs uppercase tracking-wide">Lingkungan Perangkat Lunak</h4>
            </div>
            <div class="p-8 space-y-4 text-xs font-bold text-slate-600">
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <span class="text-slate-400 uppercase tracking-wider text-[10px]">VERSI PHP</span>
                    <span class="text-slate-800">{{ $health['php_version'] }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <span class="text-slate-400 uppercase tracking-wider text-[10px]">VERSI LARAVEL</span>
                    <span class="text-slate-800">v{{ $health['laravel_version'] }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <span class="text-slate-400 uppercase tracking-wider text-[10px]">SOFTWARE SERVER</span>
                    <span class="text-slate-800 truncate max-w-[200px]" title="{{ $health['server_software'] }}">{{ $health['server_software'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 uppercase tracking-wider text-[10px]">DRIVE UTAMA (C:)</span>
                    <span class="text-slate-800">{{ $health['disk_free'] }} Bebas dari {{ $health['disk_total'] }}</span>
                </div>
            </div>
        </div>

        <!-- PHP Configuration Limits -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <i data-lucide="sliders" class="w-4 h-4 text-slate-400"></i>
                <h4 class="font-black text-slate-800 text-xs uppercase tracking-wide">Batas Konfigurasi PHP</h4>
            </div>
            <div class="p-8 space-y-4 text-xs font-bold text-slate-600">
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <span class="text-slate-400 uppercase tracking-wider text-[10px]">BATAS MEMORI (MEMORY LIMIT)</span>
                    <span class="text-slate-800">{{ $health['memory_limit'] }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <span class="text-slate-400 uppercase tracking-wider text-[10px]">BATAS UPLOAD FILE</span>
                    <span class="text-slate-800">{{ $health['upload_max'] }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <span class="text-slate-400 uppercase tracking-wider text-[10px]">BATAS POST DATA (POST MAX SIZE)</span>
                    <span class="text-slate-800">{{ $health['post_max'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 uppercase tracking-wider text-[10px]">WAKTU EKSEKUSI MAKSIMAL</span>
                    <span class="text-slate-800">{{ $health['max_execution_time'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
