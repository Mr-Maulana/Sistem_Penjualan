@extends('layouts.admin')

@section('title', isset($cashFlow) ? 'Edit Kas / Bank' : 'Tambah Kas / Bank')
@section('page-title', 'Kas / Bank')
@section('page-subtitle', isset($cashFlow) ? 'Edit transaksi kas/bank' : 'Tambah transaksi kas/bank')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 max-w-4xl mx-auto overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white">
        <h3 class="font-bold text-slate-800 text-lg">{{ isset($cashFlow) ? 'Edit Mutasi Kas' : 'Input Transaksi Kas Baru' }}</h3>
        <p class="text-xs text-slate-500 mt-1">Catat mutasi dana masuk atau keluar untuk menjaga akurasi saldo bank/kas.</p>
    </div>
    <form method="POST" action="{{ isset($cashFlow) ? route('cash-flow.update', $cashFlow) : route('cash-flow.store') }}" class="p-8">
        @csrf
        @if(isset($cashFlow))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">ID / Kode Transaksi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="hash" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="text" name="code" value="{{ old('code', $cashFlow->code ?? $autoCode ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm bg-slate-100 text-blue-600 cursor-not-allowed font-mono font-bold"
                           readonly required placeholder="Otomatis">
                </div>
                @error('code') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                <p class="text-[10px] text-slate-400 mt-1 font-medium italic">* Kode diisi otomatis oleh sistem</p>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Tanggal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="calendar" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="date" name="date" value="{{ old('date', isset($cashFlow) ? optional($cashFlow->date)->format('Y-m-d') : now()->format('Y-m-d')) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50">
                </div>
                @error('date') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Jenis Transaksi</label>
                <div class="flex gap-3">
                    @php($v = old('type', $cashFlow->type ?? 'in'))
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="type" value="in" class="hidden peer" {{ $v==='in'?'checked':'' }}>
                        <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-500 font-bold text-xs peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition-all group-hover:bg-white">
                            <i data-lucide="arrow-down-left" style="width:16px;height:16px;"></i> Masuk / Debit
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="type" value="out" class="hidden peer" {{ $v==='out'?'checked':'' }}>
                        <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-500 font-bold text-xs peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all group-hover:bg-white">
                            <i data-lucide="arrow-up-right" style="width:16px;height:16px;"></i> Keluar / Kredit
                        </div>
                    </label>
                </div>
                @error('type') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Jumlah Nominal (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <span class="text-slate-400 font-bold text-sm">Rp</span>
                    </div>
                    <input type="number" name="amount" value="{{ old('amount', $cashFlow->amount ?? 0) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-white font-mono font-black text-lg text-slate-800"
                           placeholder="0">
                </div>
                @error('amount') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Keterangan / Deskripsi</label>
            <textarea name="description" rows="3" 
                      class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                      placeholder="Masukkan detail transaksi...">{{ old('description', $cashFlow->description ?? '') }}</textarea>
            @error('description') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
        </div>

        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl px-6 py-4 flex items-center justify-between shadow-inner">
            <div class="flex items-center gap-3 text-blue-600">
                <i data-lucide="info" class="w-5 h-5"></i>
                <span class="text-sm font-semibold">Estimasi Saldo Berjalan</span>
            </div>
            <span class="font-black text-blue-700 text-lg tracking-tight">Rp {{ number_format($currentBalance ?? 0, 0, ',', '.') }}</span>
        </div>

        <div class="flex items-center gap-3 mt-10 pt-6 border-t border-slate-100">
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-3 px-10 rounded-xl text-sm transition-all shadow-lg hover:shadow-indigo-500/25 hover:-translate-y-0.5 flex items-center gap-2">
                <i data-lucide="save" style="width:18px;height:18px;"></i> Simpan Transaksi
            </button>
            <a href="{{ route('cash-flow.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-600 font-bold py-3 px-8 rounded-xl text-sm transition-all flex items-center gap-2">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

