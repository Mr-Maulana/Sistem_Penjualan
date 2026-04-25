@extends('layouts.app')

@section('title', isset($cashFlow) ? 'Edit Kas / Bank' : 'Tambah Kas / Bank')
@section('page-title', 'Kas / Bank')
@section('page-subtitle', isset($cashFlow) ? 'Edit transaksi kas/bank' : 'Tambah transaksi kas/bank')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">{{ isset($cashFlow) ? 'Edit Kas / Bank' : 'Tambah Transaksi Kas / Bank' }}</h3>
        </div>
        <form method="POST" action="{{ isset($cashFlow) ? route('cash-flow.update', $cashFlow) : route('cash-flow.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($cashFlow))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">ID</label>
                    <input name="code" value="{{ old('code', $cashFlow->code ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('code') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date', isset($cashFlow) ? optional($cashFlow->date)->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('date') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Jenis</label>
                    @php($v = old('type', $cashFlow->type ?? 'in'))
                    <select name="type" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="in" {{ $v==='in'?'selected':'' }}>Masuk</option>
                        <option value="out" {{ $v==='out'?'selected':'' }}>Keluar</option>
                    </select>
                    @error('type') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Jumlah (Rp)</label>
                    <input type="number" name="amount" value="{{ old('amount', $cashFlow->amount ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('amount') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Keterangan</label>
                <input name="description" value="{{ old('description', $cashFlow->description ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('description') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-600">
                Saldo saat ini: <span class="font-bold text-slate-800">Rp {{ number_format($currentBalance ?? 0, 0, ',', '.') }}</span>
            </div>

            <div class="flex gap-2 pt-2">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('cash-flow.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

