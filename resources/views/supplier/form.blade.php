@extends('layouts.admin')

@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('page-title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('page-subtitle', isset($supplier) ? 'Edit data supplier' : 'Input data supplier baru')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 max-w-4xl mx-auto overflow-hidden">
    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-700 text-xl tracking-tight">{{ isset($supplier) ? 'Edit Data Supplier' : 'Tambah Supplier Baru' }}</h3>
            <p class="text-xs text-slate-500 mt-1 font-normal">Lengkapi informasi suplier dan perusahaan di bawah ini.</p>
        </div>
        <div class="p-3 bg-white rounded-2xl shadow-sm">
            <i data-lucide="truck" class="w-6 h-6 text-orange-500"></i>
        </div>
    </div>
    
    <form action="{{ isset($supplier) ? route('supplier.update', $supplier) : route('supplier.store') }}" method="POST" class="p-8">
        @csrf
        @if(isset($supplier))
            @method('PUT')
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <!-- Kode & Nama -->
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">Kode Suplier</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="hash" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="code" value="{{ old('code', $supplier->code ?? $autoCode ?? '') }}" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-100 border-slate-200 rounded-2xl text-sm font-semibold text-slate-500 cursor-not-allowed shadow-sm"
                               readonly required>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">Nama Suplier</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" 
                               class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                               required placeholder="Contoh: Bpk. Heru Kurniawan">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">Nama Perusahaan</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name ?? '') }}" 
                               class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                               required placeholder="Contoh: PT. Sumber Makmur Jaya">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">NPWP <span class="text-red-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="npwp" value="{{ old('npwp', $supplier->npwp ?? '') }}" 
                               class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                               required placeholder="00.000.000.0-000.000">
                    </div>
                </div>
            </div>

            <!-- Produk & Alamat -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">Kode Produk (Prefix)</label>
                        <input type="text" name="product_code" value="{{ old('product_code', $supplier->product_code ?? '') }}" 
                               class="w-full px-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                               placeholder="Contoh: OOT" required>
                        <p class="text-[9px] text-slate-400 mt-2 font-bold italic">* Ini akan menjadi awalan kode untuk semua produk suplier ini.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">Kota / Kabupaten</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="city" value="{{ old('city', $supplier->city ?? '') }}" required
                               class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                               placeholder="Contoh: Lhokseumawe">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">Telepon</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" 
                               class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                               required placeholder="0812xxxxxx">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">Status Suplier</label>
                    <select name="status" class="w-full px-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none shadow-sm">
                        <option value="active" {{ old('status', $supplier->status ?? '') == 'active' ? 'selected' : '' }}>AKTIF</option>
                        <option value="inactive" {{ old('status', $supplier->status ?? '') == 'inactive' ? 'selected' : '' }}>NONAKTIF</option>
                    </select>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                <textarea name="address" rows="3" 
                          class="w-full px-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                          placeholder="Alamat detail perusahaan...">{{ old('address', $supplier->address ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-3 mt-10 pt-8 border-t border-slate-100">
            <button type="submit" class="w-full md:flex-1 bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-2">
                <i data-lucide="save" class="w-4 h-4 text-orange-400"></i>
                Simpan Data Supplier
            </button>
            <a href="{{ route('supplier.index') }}" class="w-full md:w-auto px-10 py-4 bg-white border border-slate-200 text-slate-600 font-black rounded-2xl hover:bg-slate-50 transition-all flex items-center justify-center">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companyInput = document.querySelector('input[name="company_name"]');
    const codeInput = document.querySelector('input[name="code"]');
    const isEdit = {{ isset($supplier) ? 'true' : 'false' }};

    if (!isEdit) {
        companyInput.addEventListener('input', function() {
            const val = this.value.trim();
            if (val.length >= 2) {
                // Remove PT., CV., etc.
                let cleanName = val.replace(/^(PT\.|CV\.|UD\.|PT|CV|UD)\s+/i, '');
                let words = cleanName.split(/\s+/);
                let prefix = '';
                
                if (words.length >= 2) {
                    prefix = (words[0][0] + words[1][0]).toUpperCase();
                } else {
                    prefix = words[0].substring(0, 2).toUpperCase();
                }

                // Append random number or just use prefix
                // For now, let's use prefix + count (we'll assume 001 for suggested)
                codeInput.value = prefix + '-001';
                codeInput.classList.remove('bg-slate-100', 'text-slate-500', 'cursor-not-allowed');
                codeInput.classList.add('bg-white', 'text-slate-800');
                codeInput.readOnly = false;
            }
        });
    }
});
</script>
@endpush
@endsection
