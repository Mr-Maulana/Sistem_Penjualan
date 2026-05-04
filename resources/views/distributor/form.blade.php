@extends('layouts.admin')

@section('title', isset($distributor) ? 'Edit Distributor' : 'Tambah Distributor')
@section('page-title', isset($distributor) ? 'Edit Distributor' : 'Tambah Distributor')
@section('page-subtitle', isset($distributor) ? 'Edit data distributor' : 'Input data distributor baru')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 max-w-3xl mx-auto overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white">
        <h3 class="font-bold text-slate-800 text-lg">{{ isset($distributor) ? 'Edit Data Distributor' : 'Tambah Distributor Baru' }}</h3>
        <p class="text-xs text-slate-500 mt-1">Isi formulir di bawah ini dengan lengkap dan benar.</p>
    </div>
    
    <form action="{{ isset($distributor) ? route('distributor.update', $distributor) : route('distributor.store') }}" method="POST" class="p-8">
        @csrf
        @if(isset($distributor))
            @method('PUT')
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Distributor</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="hash" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="text" name="code" value="{{ old('code', $distributor->code ?? $autoCode ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm bg-slate-100 text-slate-500 cursor-not-allowed font-bold"
                           readonly required placeholder="Otomatis">
                </div>
                @error('code')
                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
                <p class="text-[10px] text-slate-400 mt-1 font-medium italic">* Kode diisi otomatis oleh sistem</p>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Distributor</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="building" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="text" name="name" value="{{ old('name', $distributor->name ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           required placeholder="Nama Perusahaan">
                </div>
                @error('name')
                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Kota</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="map-pin" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="text" name="city" value="{{ old('city', $distributor->city ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           required placeholder="Kota Domisili">
                </div>
                @error('city')
                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Telepon</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="phone" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="text" name="phone" value="{{ old('phone', $distributor->phone ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           required placeholder="0812xxxxxx">
                </div>
                @error('phone')
                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Alamat Lengkap</label>
                <textarea name="address" rows="3" 
                          class="w-full border border-slate-200 rounded-xl p-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                          placeholder="Alamat detail...">{{ old('address', $distributor->address ?? '') }}</textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Status</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all bg-slate-50/50 hover:bg-slate-50 appearance-none">
                    <option value="active" {{ old('status', $distributor->status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $distributor->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
            <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                <i data-lucide="save" style="width:16px;height:16px;"></i> Simpan Data
            </button>
            <a href="{{ route('distributor.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-600 font-bold py-2.5 px-6 rounded-xl text-sm transition-all flex items-center gap-2">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
