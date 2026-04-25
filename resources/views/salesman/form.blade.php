@extends('layouts.app')

@section('title', isset($salesman) ? 'Edit Salesman' : 'Tambah Salesman')
@section('page-title', 'Salesman')
@section('page-subtitle', isset($salesman) ? 'Edit data salesman' : 'Tambah data salesman')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">{{ isset($salesman) ? 'Edit Salesman' : 'Tambah Salesman' }}</h3>
        </div>
        <form method="POST" action="{{ isset($salesman) ? route('salesman.update', $salesman) : route('salesman.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($salesman))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">ID Salesman</label>
                    <input name="code" value="{{ old('code', $salesman->code ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('code') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                    <select name="status" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @php($v = old('status', $salesman->status ?? 'active'))
                        <option value="active" {{ $v==='active'?'selected':'' }}>Aktif</option>
                        <option value="inactive" {{ $v==='inactive'?'selected':'' }}>Nonaktif</option>
                    </select>
                    @error('status') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Nama</label>
                <input name="name" value="{{ old('name', $salesman->name ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('name') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Area</label>
                    <input name="area" value="{{ old('area', $salesman->area ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('area') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Telepon</label>
                    <input name="phone" value="{{ old('phone', $salesman->phone ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('phone') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Target (Rp)</label>
                <input type="number" name="target" value="{{ old('target', $salesman->target ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('target') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-2 pt-2">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('salesman.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

