@extends('layouts.admin')

@section('title', isset($salesman) ? 'Edit Salesman' : 'Tambah Salesman')
@section('page-title', 'Salesman')
@section('page-subtitle', isset($salesman) ? 'Edit data salesman' : 'Tambah data salesman')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 max-w-4xl mx-auto overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white">
        <h3 class="font-bold text-slate-800 text-lg">{{ isset($salesman) ? 'Edit Data Salesman' : 'Tambah Salesman Baru' }}</h3>
        <p class="text-xs text-slate-500 mt-1">Isi formulir di bawah ini dengan lengkap dan benar.</p>
    </div>
    <form method="POST" action="{{ isset($salesman) ? route('salesman.update', $salesman) : route('salesman.store') }}" class="p-8">
        @csrf
        @if(isset($salesman))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">ID Salesman</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="hash" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="text" name="code" value="{{ old('code', $salesman->code ?? $autoCode ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm bg-slate-100 text-slate-500 cursor-not-allowed font-bold"
                           readonly required placeholder="Otomatis">
                </div>
                @error('code') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                <p class="text-[10px] text-slate-400 mt-1 font-medium italic">* ID diisi otomatis oleh sistem</p>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Status</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all bg-slate-50/50 hover:bg-slate-50 appearance-none">
                    @php($v = old('status', $salesman->status ?? 'active'))
                    <option value="active" {{ $v==='active'?'selected':'' }}>Aktif</option>
                    <option value="inactive" {{ $v==='inactive'?'selected':'' }}>Nonaktif</option>
                </select>
                @error('status') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="user" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input name="name" value="{{ old('name', $salesman->name ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           required placeholder="Nama Salesman">
                </div>
                @error('name') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Area</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="map" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input name="area" value="{{ old('area', $salesman->area ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           required placeholder="Area Penjualan">
                </div>
                @error('area') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Telepon</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="phone" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input name="phone" value="{{ old('phone', $salesman->phone ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           required placeholder="0812xxxxxx">
                </div>
                @error('phone') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Target Bulanan (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="target" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="number" name="target" value="{{ old('target', $salesman->target ?? 0) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all bg-slate-50/50 hover:bg-slate-50 font-mono font-semibold"
                           required placeholder="Contoh: 10000000">
                </div>
                @error('target') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
            <button type="submit" class="bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                <i data-lucide="save" style="width:16px;height:16px;"></i> Simpan Data
            </button>
            <a href="{{ route('salesman.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-600 font-bold py-2.5 px-6 rounded-xl text-sm transition-all flex items-center gap-2">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

