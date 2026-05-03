@extends('layouts.admin')

@section('title', isset($distributor) ? 'Edit Distributor' : 'Tambah Distributor')
@section('page-title', isset($distributor) ? 'Edit Distributor' : 'Tambah Distributor')
@section('page-subtitle', isset($distributor) ? 'Edit data distributor' : 'Input data distributor baru')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 max-w-2xl mx-auto">
    <form action="{{ isset($distributor) ? route('distributor.update', $distributor) : route('distributor.store') }}" method="POST" class="p-6">
        @csrf
        @if(isset($distributor))
            @method('PUT')
        @endif
        
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Kode Distributor</label>
                <input type="text" name="code" value="{{ old('code', $distributor->code ?? '') }}" 
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('code') border-red-500 @enderror"
                       required>
                @error('code')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Distributor</label>
                <input type="text" name="name" value="{{ old('name', $distributor->name ?? '') }}" 
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Kota</label>
                <input type="text" name="city" value="{{ old('city', $distributor->city ?? '') }}" 
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('city') border-red-500 @enderror"
                       required>
                @error('city')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $distributor->phone ?? '') }}" 
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('phone') border-red-500 @enderror"
                       required>
                @error('phone')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Alamat</label>
                <textarea name="address" rows="3" 
                          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('address', $distributor->address ?? '') }}</textarea>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                <select name="status" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="active" {{ old('status', $distributor->status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $distributor->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            
            <div class="flex gap-2 pt-4">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg text-sm transition">
                    Simpan
                </button>
                <a href="{{ route('distributor.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 rounded-lg text-sm transition text-center">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
