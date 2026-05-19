@extends('layouts.admin')

@section('title', isset($price) ? 'Edit Harga' : 'Tambah Harga')
@section('page-title', 'Harga')
@section('page-subtitle', isset($price) ? 'Edit harga produk' : 'Tambah harga produk')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 max-w-4xl mx-auto overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white">
        <h3 class="font-bold text-slate-800 text-lg">{{ isset($price) ? 'Edit Penetapan Harga' : 'Tambah Penetapan Harga' }}</h3>
        <p class="text-xs text-slate-500 mt-1">Tentukan harga khusus untuk produk dan grup customer tertentu.</p>
    </div>
    <form method="POST" action="{{ isset($price) ? route('price.update', $price) : route('price.store') }}" class="p-8">
        @csrf
        @if(isset($price))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Pilih Produk</label>
                <div class="relative product-searchable-select w-full">
                    <!-- Hidden input to store selected product code -->
                    <input type="hidden" name="product_code" id="product_code_input" value="{{ old('product_code', $price->product_code ?? '') }}">
                    
                    <!-- Select Trigger Button -->
                    <button type="button" class="select-trigger w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-left bg-slate-50/50 hover:bg-slate-50 flex items-center justify-between focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all">
                        <span class="trigger-label truncate font-medium text-slate-800">
                            @php
                                $selectedProductCode = old('product_code', $price->product_code ?? '');
                                $selProduct = $products->firstWhere('code', $selectedProductCode);
                            @endphp
                            {{ $selProduct ? '[' . $selProduct->code . '] - ' . $selProduct->name : '-- Pilih Produk --' }}
                        </span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 ml-1.5"></i>
                    </button>
                    
                    <!-- Icon overlay for visual consistency -->
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="package" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    
                    <!-- Dropdown Option List -->
                    <div class="select-dropdown hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-3">
                        <!-- Search input -->
                        <div class="relative mb-2">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                            <input type="text" class="dropdown-search-input w-full border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 bg-slate-50/50" placeholder="Cari kode atau nama produk...">
                        </div>
                        
                        <!-- Options scrollable container -->
                        <div class="options-list max-h-56 overflow-y-auto divide-y divide-slate-100">
                            @foreach($products as $p)
                                <button type="button" class="option-item w-full text-left px-3 py-2.5 text-sm hover:bg-amber-50 hover:text-amber-700 transition-colors flex flex-col gap-0.5 rounded-lg" 
                                        data-code="{{ $p->code }}" 
                                        data-name="{{ $p->name }}">
                                    <span class="font-bold font-mono text-xs text-slate-500 option-code">[{{ $p->code }}]</span>
                                    <span class="font-medium option-name text-slate-800">{{ $p->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                @error('product_code') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Grup Customer</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="users" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input name="customer_group" value="{{ old('customer_group', $price->customer_group ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           placeholder="Contoh: Grosir / Retail / VIP">
                </div>
                @error('customer_group') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Harga Satuan Besar (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <span class="text-slate-400 font-bold text-sm">Rp</span>
                    </div>
                    <input type="number" name="price_large" value="{{ old('price_large', $price->price_large ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all bg-slate-50/50 hover:bg-slate-50 font-mono font-semibold"
                           placeholder="0">
                </div>
                @error('price_large') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Harga Satuan Kecil (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <span class="text-slate-400 font-bold text-sm">Rp</span>
                    </div>
                    <input type="number" name="price_small" value="{{ old('price_small', $price->price_small ?? '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all bg-slate-50/50 hover:bg-slate-50 font-mono font-semibold"
                           placeholder="0">
                </div>
                @error('price_small') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Diskon (%)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="percent" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="number" name="discount" value="{{ old('discount', $price->discount ?? 0) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           min="0" max="100">
                </div>
                @error('discount') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Pajak / PPN (%)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="percent" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="number" name="tax" value="{{ old('tax', $price->tax ?? 0) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                           min="0" max="100">
                </div>
                @error('tax') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Tanggal Mulai Berlaku</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="calendar" style="width:16px;height:16px;" class="text-slate-400"></i>
                    </div>
                    <input type="date" name="effective_date" value="{{ old('effective_date', isset($price) ? optional($price->effective_date)->format('Y-m-d') : '') }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all bg-slate-50/50 hover:bg-slate-50">
                </div>
                @error('effective_date') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
            <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                <i data-lucide="save" style="width:16px;height:16px;"></i> Simpan Harga
            </button>
            <a href="{{ route('price.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-600 font-bold py-2.5 px-6 rounded-xl text-sm transition-all flex items-center gap-2">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchSelect = document.querySelector('.product-searchable-select');
    if (searchSelect) {
        const hiddenInput = searchSelect.querySelector('#product_code_input');
        const trigger = searchSelect.querySelector('.select-trigger');
        const dropdown = searchSelect.querySelector('.select-dropdown');
        const searchInput = searchSelect.querySelector('.dropdown-search-input');
        const options = searchSelect.querySelectorAll('.option-item');
        
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                searchInput.value = '';
                options.forEach(o => o.classList.remove('hidden'));
                searchInput.focus();
            }
        });
        
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            options.forEach(opt => {
                const code = opt.getAttribute('data-code').toLowerCase();
                const name = opt.getAttribute('data-name').toLowerCase();
                if (code.includes(query) || name.includes(query)) {
                    opt.classList.remove('hidden');
                } else {
                    opt.classList.add('hidden');
                }
            });
        });
        
        options.forEach(opt => {
            opt.addEventListener('click', () => {
                const code = opt.getAttribute('data-code');
                const name = opt.getAttribute('data-name');
                
                hiddenInput.value = code;
                trigger.querySelector('.trigger-label').textContent = `[${code}] - ${name}`;
                dropdown.classList.add('hidden');
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchSelect.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush

