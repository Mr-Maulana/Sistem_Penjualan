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
    <form method="POST" action="{{ isset($salesman) ? route('salesman.update', $salesman) : route('salesman.store') }}" class="p-8 lg:p-10" enctype="multipart/form-data" x-data="{ photoPreview: null }">
        @csrf
        @if(isset($salesman))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Photo Upload Section -->
            <div class="lg:col-span-4 flex flex-col items-center space-y-6">
                <div class="relative group">
                    <div class="w-48 h-48 rounded-3xl overflow-hidden border-4 border-white shadow-2xl shadow-slate-200 bg-slate-50 relative">
                        <!-- Current Photo or Placeholder -->
                        <template x-if="!photoPreview">
                            @if(isset($salesman) && $salesman->photo)
                                <img src="{{ asset('storage/' . $salesman->photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                    <i data-lucide="user" class="w-16 h-16 mb-2"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">No Photo</span>
                                </div>
                            @endif
                        </template>
                        <!-- Preview Photo -->
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                            <label for="photo_file" class="cursor-pointer bg-white text-orange-600 p-3 rounded-2xl shadow-xl transform scale-90 group-hover:scale-100 transition-transform border border-orange-100">
                                <i data-lucide="camera" class="w-6 h-6"></i>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-3 -right-3 bg-white p-2 rounded-2xl shadow-lg border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-inner">
                            <i data-lucide="image-plus" class="w-5 h-5"></i>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <h5 class="text-sm font-black text-slate-800 mb-1">Foto Profil</h5>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">JPG, PNG atau WEBP (Max. 2MB)</p>
                </div>
                
                <input type="file" name="photo_file" id="photo_file" class="hidden" accept="image/*" 
                       @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result }; reader.readAsDataURL(file); }">
                @error('photo_file') <div class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</div> @enderror
            </div>

            <!-- Fields Section -->
            <div class="lg:col-span-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Identitas Utama</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                    <i data-lucide="hash" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="code" value="{{ old('code', $salesman->code ?? $autoCode ?? '') }}" 
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-400 cursor-not-allowed shadow-inner"
                                       readonly required placeholder="Otomatis">
                            </div>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                    <i data-lucide="info" class="w-4 h-4"></i>
                                </div>
                                <select name="status" class="w-full pl-11 pr-4 py-3 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none shadow-sm">
                                    @php($v = old('status', $salesman->status ?? 'active'))
                                    <option value="active" {{ $v==='active'?'selected':'' }}>AKTIF</option>
                                    <option value="inactive" {{ $v==='inactive'?'selected':'' }}>NONAKTIF</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 relative group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Nama Lengkap Sesuai KTP</label>
                        <div class="absolute inset-y-0 left-0 pl-4 pt-8 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input name="name" value="{{ old('name', $salesman->name ?? '') }}" 
                               class="w-full pl-12 pr-4 py-4 bg-white border-slate-200 rounded-2xl text-base font-black text-slate-800 placeholder:text-slate-300 focus:ring-8 focus:ring-orange-500/5 focus:border-orange-500 transition-all shadow-sm"
                               required placeholder="Masukkan nama lengkap...">
                        @error('name') <div class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">NIK (16 Digit) <span class="text-red-500">*</span></label>
                        <input name="nik" value="{{ old('nik', $salesman->nik ?? '') }}" 
                               class="w-full px-5 py-3 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                               required placeholder="357xxxxxxxxxxxxx">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">NPWP <span class="text-red-500">*</span></label>
                        <input name="npwp" value="{{ old('npwp', $salesman->npwp ?? '') }}" 
                               class="w-full px-5 py-3 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                               required placeholder="00.000.000.0-000.000">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Email Perusahaan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $salesman->email ?? '') }}" 
                                   class="w-full pl-11 pr-4 py-3 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                                   placeholder="email@perusahaan.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">WhatsApp / Telepon</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <input name="phone" value="{{ old('phone', $salesman->phone ?? '') }}" 
                                   class="w-full pl-11 pr-4 py-3 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                                   required placeholder="0812xxxxxxxx">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Alamat Lengkap Domisili</label>
                        <textarea name="address" rows="3" 
                                  class="w-full px-5 py-4 bg-white border-slate-200 rounded-3xl text-sm font-semibold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm"
                                  placeholder="Masukkan alamat lengkap sesuai domisili saat ini...">{{ old('address', $salesman->address ?? '') }}</textarea>
                    </div>

                    <div class="md:col-span-2 pt-4 border-t border-slate-100">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Penempatan & Struktur</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Level Organisasi</label>
                                <select name="level" class="w-full px-5 py-3.5 bg-slate-50 border-slate-200 rounded-2xl text-sm font-black text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none shadow-inner">
                                    @php($lvl = old('level', $salesman->level ?? 'sales'))
                                    <option value="sales" {{ $lvl==='sales'?'selected':'' }}>SALES (Wilayah / Kecamatan)</option>
                                    <option value="supervisor" {{ $lvl==='supervisor'?'selected':'' }}>SUPERVISOR (Kota)</option>
                                    <option value="manager" {{ $lvl==='manager'?'selected':'' }}>MANAGER (Provinsi)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Target Bulanan (Rp)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i data-lucide="target" class="w-4 h-4"></i>
                                    </div>
                                    <input type="number" name="target" value="{{ old('target', $salesman->target ?? 0) }}" 
                                           class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-sm font-mono"
                                           required placeholder="0">
                                </div>
                            </div>

                            <div class="md:col-span-2 space-y-6">
                                <!-- Area Selection Section Based on Level -->
                                <div id="manager_area_section" class="hidden space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Provinsi Operasional (Manager)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i data-lucide="globe" class="w-4 h-4"></i>
                                            </div>
                                            <select id="area_province" class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none shadow-sm">
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach($areas->pluck('province')->unique()->sort() as $prov)
                                                    <option value="{{ $prov }}" {{ old('area', $salesman->area ?? '') == $prov ? 'selected' : '' }}>
                                                        {{ strtoupper($prov) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="supervisor_area_section" class="hidden space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Kota Operasional (Supervisor)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i data-lucide="building" class="w-4 h-4"></i>
                                            </div>
                                            <select id="city_supervisor" class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none shadow-sm">
                                                <option value="">-- Pilih Kota --</option>
                                                @foreach($areas->unique('city')->sortBy('city') as $area)
                                                    <option value="{{ $area->city }}" {{ old('city', $salesman->city ?? '') == $area->city ? 'selected' : '' }}>
                                                        {{ strtoupper($area->province) }} - {{ strtoupper($area->city) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="sales_area_section" class="hidden">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Wilayah / Kecamatan (Sales)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                                </div>
                                                <select id="area_sales" class="w-full pl-11 pr-4 py-3.5 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none shadow-sm">
                                                    <option value="">-- Pilih Kecamatan --</option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->code }}" data-city="{{ $area->city }}" {{ old('area', $salesman->area ?? '') == $area->code ? 'selected' : '' }}>
                                                            {{ strtoupper($area->province) }} - {{ strtoupper($area->city) }} - {{ strtoupper($area->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 mb-2 uppercase">Kota Operasional</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                    <i data-lucide="building" class="w-4 h-4"></i>
                                                </div>
                                                <input type="text" id="city_sales" value="{{ old('city', $salesman->city ?? '') }}" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-500 cursor-not-allowed shadow-inner" readonly placeholder="Otomatis terisi...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('area') <div class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</div> @enderror
                                @error('city') <div class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Atasan Langsung (Hirarki)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="network" class="w-5 h-5"></i>
                            </div>
                            <select name="supervisor_id" id="supervisor_id" class="w-full pl-12 pr-4 py-4 bg-white text-slate-800 border border-slate-200 rounded-3xl text-sm font-black focus:ring-8 focus:ring-orange-500/5 focus:border-orange-500 transition-all appearance-none shadow-sm">
                                <option value="">-- TANPA ATASAN (PUCUK STRUKTUR) --</option>
                                @foreach($supervisors as $sup)
                                    <option value="{{ $sup->id }}" 
                                            data-level="{{ $sup->level }}" 
                                            data-city="{{ strtolower(trim($sup->city)) }}"
                                            data-province="{{ strtolower(trim($sup->area)) }}"
                                            {{ old('supervisor_id', $salesman->supervisor_id ?? '') == $sup->id ? 'selected' : '' }}>
                                        {{ strtoupper($sup->name) }} [{{ strtoupper($sup->level) }} - {{ $sup->city ?? $sup->area }}]
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-500">
                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-3 font-bold italic">* Sistem akan memvalidasi hirarki sesuai wilayah operasional.</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-12">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-black py-4 px-8 rounded-3xl text-sm transition-all shadow-2xl shadow-orange-500/20 hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        SIMPAN DATA SALESMAN
                    </button>
                    <a href="{{ route('salesman.index') }}" class="px-8 py-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-black rounded-3xl text-sm transition-all">
                        BATAL
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Re-initialize Lucide icons for Alpine templates
    if (window.lucide) lucide.createIcons();

    const levelSelect = document.querySelector('select[name="level"]');
    const supervisorSelect = document.getElementById('supervisor_id');
    const allOptions = Array.from(supervisorSelect.options).filter(opt => opt.value !== "");

    // Dynamic Area Elements
    const managerSection = document.getElementById('manager_area_section');
    const supervisorSection = document.getElementById('supervisor_area_section');
    const salesSection = document.getElementById('sales_area_section');

    const areaProvince = document.getElementById('area_province');
    const citySupervisor = document.getElementById('city_supervisor');
    const areaSales = document.getElementById('area_sales');
    const citySales = document.getElementById('city_sales');

    function handleAreaFields() {
        const level = levelSelect.value;

        // Hide all first
        managerSection.classList.add('hidden');
        supervisorSection.classList.add('hidden');
        salesSection.classList.add('hidden');

        // Remove names so they don't submit
        areaProvince.removeAttribute('name');
        citySupervisor.removeAttribute('name');
        areaSales.removeAttribute('name');
        citySales.removeAttribute('name');

        if (level === 'manager') {
            managerSection.classList.remove('hidden');
            areaProvince.setAttribute('name', 'area');
        } else if (level === 'supervisor') {
            supervisorSection.classList.remove('hidden');
            citySupervisor.setAttribute('name', 'city');
        } else if (level === 'sales') {
            salesSection.classList.remove('hidden');
            areaSales.setAttribute('name', 'area');
            citySales.setAttribute('name', 'city');
        }

        // Run supervisor filter whenever fields adjust
        filterSupervisors();
    }

    function filterSupervisors() {
        const level = levelSelect.value;
        
        // Get current selected city name
        let city = '';
        if (level === 'supervisor') {
            city = citySupervisor.value.toLowerCase().trim();
        } else if (level === 'sales') {
            city = citySales.value.toLowerCase().trim();
        }
        
        const currentSupervisorId = supervisorSelect.value;

        supervisorSelect.innerHTML = '<option value="">-- TANPA ATASAN (PUCUK STRUKTUR) --</option>';

        let targetLevel = '';
        if (level === 'sales') targetLevel = 'supervisor';
        else if (level === 'supervisor') targetLevel = 'manager';

        // Filter superiors
        const filtered = allOptions.filter(opt => {
            const optLevel = opt.dataset.level;
            const optCity = opt.dataset.city;
            
            if (level === 'manager') return false;
            
            // If under a Manager, manager doesn't have a specific city, but they do have a Province (area).
            // So if targetLevel is 'manager', we need to check if the current city belongs to the manager's province!
            if (optLevel === 'manager') {
                const optProvince = opt.dataset.province;
                
                // Get the province for the current city
                const selectedOption = (level === 'supervisor') 
                    ? citySupervisor.options[citySupervisor.selectedIndex]
                    : areaSales.options[areaSales.selectedIndex];
                
                let selectedProvince = '';
                if (selectedOption && level === 'sales') {
                    const parts = selectedOption.text.split(' - ');
                    if (parts.length > 0) selectedProvince = parts[0].trim().toLowerCase();
                } else if (selectedOption && level === 'supervisor') {
                    const parts = selectedOption.text.split(' - ');
                    if (parts.length > 0) selectedProvince = parts[0].trim().toLowerCase();
                }
                
                return optProvince && selectedProvince && optProvince.toLowerCase().trim() === selectedProvince;
            }
            
            return optLevel === targetLevel && optCity === city;
        });

        filtered.forEach(opt => supervisorSelect.appendChild(opt.cloneNode(true)));
        if (filtered.some(opt => opt.value === currentSupervisorId)) {
            supervisorSelect.value = currentSupervisorId;
        }
        
        if (window.lucide) lucide.createIcons();
    }

    // Auto fill city for sales when kecamatan changes
    areaSales.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.city) {
            citySales.value = selectedOption.dataset.city;
        } else {
            citySales.value = '';
        }
        filterSupervisors();
    });

    citySupervisor.addEventListener('change', filterSupervisors);
    levelSelect.addEventListener('change', handleAreaFields);

    // Initial setup
    handleAreaFields();
    
    // If editing, auto-fill citySales
    const initialSalesOption = areaSales.options[areaSales.selectedIndex];
    if (initialSalesOption && initialSalesOption.dataset.city) {
        citySales.value = initialSalesOption.dataset.city;
    }
    
    filterSupervisors();
});
</script>
@endpush
@endsection

