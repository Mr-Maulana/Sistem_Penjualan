@extends('layouts.admin')

@section('title', 'Detail Arus Kas')
@section('page-title', 'Detail Arus Kas')
@section('page-subtitle', 'Informasi lengkap transaksi kas/bank')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl {{ $cashFlow->type == 'in' ? 'bg-emerald-50 text-emerald-600 ring-emerald-500/20' : 'bg-red-50 text-red-600 ring-red-500/20' }} flex items-center justify-center font-bold text-xl ring-1">
                    <i data-lucide="{{ $cashFlow->type == 'in' ? 'arrow-down-left' : 'arrow-up-right' }}" style="width:24px;height:24px;"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">{{ $cashFlow->code }}</h3>
                    <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-0.5">
                        <i data-lucide="calendar" style="width:14px;height:14px;"></i> {{ $cashFlow->date->format('d M Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('cash-flow.edit', $cashFlow) }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all shadow-sm">
                    <i data-lucide="pencil" style="width:16px;height:16px;"></i> Edit
                </a>
                <a href="{{ route('cash-flow.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all">
                    <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali
                </a>
            </div>
        </div>
        
        <div class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe Transaksi</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $cashFlow->type == 'in' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $cashFlow->type == 'in' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ $cashFlow->type == 'in' ? 'Uang Masuk (Debit)' : 'Uang Keluar (Kredit)' }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Jumlah Nominal</p>
                    <p class="text-2xl font-black {{ $cashFlow->type == 'in' ? 'text-emerald-600' : 'text-red-600' }} tracking-tight">
                        {{ $cashFlow->type == 'in' ? '+' : '-' }} Rp {{ number_format($cashFlow->amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Deskripsi / Keterangan</p>
                <p class="text-slate-700 font-medium leading-relaxed">{{ $cashFlow->description }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Saldo Setelah Transaksi</p>
                    <p class="text-lg font-bold text-slate-800">Rp {{ number_format($cashFlow->balance, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Dibuat Pada</p>
                    <p class="text-sm text-slate-600 font-medium">{{ $cashFlow->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
