@extends('layouts.admin')

@section('title', 'Detail Customer')
@section('page-title', 'Detail Customer')
@section('page-subtitle', 'Informasi detail pelanggan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden max-w-4xl mx-auto">
    <div class="px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl ring-1 ring-indigo-500/20">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">{{ $customer->name }}</h3>
                <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-0.5">
                    <i data-lucide="users" style="width:14px;height:14px;"></i> Customer {{ $customer->group ? ' - ' . $customer->group : '' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.edit', $customer) }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all shadow-sm">
                <i data-lucide="pencil" style="width:16px;height:16px;"></i> Edit
            </a>
            <a href="{{ route('customer.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode Customer</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="hash" style="width:18px;height:18px;" class="text-indigo-500"></i>
                    <p class="font-mono text-slate-800 font-semibold text-lg">{{ $customer->code }}</p>
                </div>
            </div>
            
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium {{ $customer->status == 'active' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $customer->status == 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    {{ $customer->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Telepon</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="phone" style="width:18px;height:18px;" class="text-slate-400"></i>
                    <p class="text-slate-800 font-semibold text-base">{{ $customer->phone }}</p>
                </div>
            </div>
            
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kota Domisili</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="map-pin" style="width:18px;height:18px;" class="text-slate-400"></i>
                    <p class="text-slate-800 font-semibold text-base">{{ $customer->city ?? '-' }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Grup</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="tag" style="width:18px;height:18px;" class="text-slate-400"></i>
                    <p class="text-slate-800 font-semibold text-base">{{ $customer->group ?? '-' }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Salesman (PIC)</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="user-check" style="width:18px;height:18px;" class="text-slate-400"></i>
                    <p class="text-slate-800 font-semibold text-base">{{ $customer->salesman->name ?? 'Belum ada PIC' }}</p>
                </div>
            </div>
            
            <div class="md:col-span-2 bg-slate-50/50 rounded-2xl p-6 border border-slate-100 mt-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Lengkap</p>
                <div class="flex items-start gap-2.5">
                    <i data-lucide="map" style="width:20px;height:20px;" class="text-slate-400 mt-0.5"></i>
                    <p class="text-slate-700 text-base leading-relaxed">{{ $customer->address ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
