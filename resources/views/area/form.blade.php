@extends('layouts.admin')

@section('title', isset($area) ? 'Edit Wilayah' : 'Tambah Wilayah')
@section('page-title', 'Wilayah')
@section('page-subtitle', isset($area) ? 'Update data operasional wilayah' : 'Daftarkan area operasional baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ isset($area) ? 'Edit Wilayah' : 'Tambah Wilayah Baru' }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Pastikan kode area bersifat unik.</p>
            </div>
            <div class="p-3 bg-white rounded-2xl shadow-sm">
                <i data-lucide="map" class="w-6 h-6 text-orange-500"></i>
            </div>
        </div>

        <form action="{{ isset($area) ? route('area.update', $area) : route('area.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            @if(isset($area)) @method('PUT') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Provinsi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="globe" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="province" value="{{ old('province', $area->province ?? 'Aceh') }}" required
                            class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                            placeholder="Contoh: Aceh">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kota / Kabupaten</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="city" value="{{ old('city', $area->city ?? '') }}" required
                            class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                            placeholder="Contoh: Lhokseumawe">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kecamatan</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name', $area->name ?? '') }}" required
                            class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                            placeholder="Contoh: Kuta Blang">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kode Area</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="hash" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="code" value="{{ old('code', $area->code ?? '') }}" required
                            class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                            placeholder="Contoh: LHK-KBL">
                    </div>
                    @error('code') <p class="text-xs text-red-500 mt-2 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 flex flex-col md:flex-row items-center gap-3">
                <button type="submit" class="w-full md:flex-1 bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4 text-orange-400"></i>
                    Simpan Data Wilayah
                </button>
                <a href="{{ route('area.index') }}" class="w-full md:w-auto px-8 py-4 bg-white border border-slate-200 text-slate-600 font-black rounded-2xl hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
