@extends('layouts.admin')

@section('title', 'Detail Harga')
@section('page-title', 'Detail Harga')
@section('page-subtitle', 'Informasi detail penetapan harga produk')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden max-w-4xl mx-auto">
    <div class="px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl ring-1 ring-amber-500/20">
                <i data-lucide="tag" style="width:24px;height:24px;"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">{{ $price->product->name }}</h3>
                <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-0.5">
                    Grup Customer: {{ $price->customer_group ?? 'Umum (Default)' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('price.edit', $price) }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all shadow-sm">
                <i data-lucide="pencil" style="width:16px;height:16px;"></i> Edit
            </a>
            <a href="{{ route('price.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode Produk</p>
                <p class="font-mono text-slate-800 font-semibold text-lg">{{ $price->product->code }}</p>
            </div>
            
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tanggal Efektif</p>
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar" style="width:18px;height:18px;" class="text-amber-500"></i>
                    <p class="text-slate-800 font-semibold text-base">{{ $price->effective_date ? $price->effective_date->format('d M Y') : 'Berlaku Selamanya' }}</p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Struktur Harga</p>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Harga Satuan Besar</span>
                        <span class="font-bold text-slate-800 tracking-tight">Rp {{ number_format($price->price_large, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Harga Satuan Kecil</span>
                        <span class="font-bold text-slate-800 tracking-tight">Rp {{ number_format($price->price_small, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Potongan & Pajak</p>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Diskon</span>
                        <span class="font-bold text-emerald-600 tracking-tight">{{ $price->discount }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Pajak (PPN)</span>
                        <span class="font-bold text-amber-600 tracking-tight">{{ $price->tax }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
