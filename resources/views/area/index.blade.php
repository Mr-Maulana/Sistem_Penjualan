@extends('layouts.admin')

@section('title', 'Master Wilayah / Area')
@section('page-title', 'Wilayah & Area')
@section('page-subtitle', 'Kelola data pembagian wilayah secara terstruktur')

@section('content')
@php $isAdmin = auth()->user()->role === 'admin'; @endphp

<div class="space-y-6">
    <!-- Header Search Bar -->
    <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200/60">
        <form action="{{ route('area.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            @if($selectedProvince)
                <input type="hidden" name="province" value="{{ $selectedProvince }}">
            @endif
            @if($selectedCity)
                <input type="hidden" name="city" value="{{ $selectedCity }}">
            @endif
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all" 
                    placeholder="Cari kode area, nama kecamatan, kota, atau provinsi...">
            </div>
            <button type="submit" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-2xl text-xs uppercase tracking-widest hover:bg-slate-800 transition-all">
                Cari Wilayah
            </button>
            @if(request('search'))
                <a href="{{ route('area.index') }}" class="px-5 py-3 bg-slate-100 text-slate-500 rounded-2xl font-bold text-xs uppercase tracking-widest flex items-center hover:bg-slate-200 transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Alert Success/Error -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 text-sm font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-800 text-sm font-bold flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(request('search'))
        <!-- ============================================== -->
        <!--               SEARCH RESULTS VIEW              -->
        <!-- ============================================== -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-slate-800 text-sm uppercase tracking-wide">Hasil Pencarian: "{{ request('search') }}"</h3>
                    <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider">Ditemukan {{ $searchResults->count() }} Wilayah</p>
                </div>
                <a href="{{ route('area.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all text-xs">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    Kembali
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/30 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Provinsi</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kota / Kab</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kecamatan</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kode Kecamatan</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kode Area Lengkap</th>
                            @if($isAdmin)
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-bold">
                        @forelse($searchResults as $res)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-8 py-4 text-slate-500 uppercase tracking-tight">{{ $res->province }} ({{ $res->province_code }})</td>
                                <td class="px-8 py-4 text-slate-800">{{ $res->city }} ({{ $res->city_code }})</td>
                                <td class="px-8 py-4 text-orange-600">{{ $res->name }}</td>
                                <td class="px-8 py-4 text-center">
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-black rounded border border-slate-200">
                                        {{ $res->kecamatan_code }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="px-2.5 py-0.5 bg-orange-50 text-orange-600 text-[9px] font-black rounded border border-orange-100">
                                        {{ $res->code }}
                                    </span>
                                </td>
                                @if($isAdmin)
                                    <td class="px-8 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="openKecamatanModal('{{ $res->province }}', '{{ $res->city }}', '{{ $res->id }}', '{{ $res->name }}', '{{ $res->kecamatan_code }}')" 
                                                    class="p-2 text-slate-400 hover:text-orange-500 hover:bg-orange-50 rounded-xl transition-all" title="Edit Kecamatan">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="openDeleteKecamatanModal('{{ $res->id }}', '{{ $res->name }}')" 
                                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Hapus Kecamatan">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="map" class="w-12 h-12 mb-4 opacity-20"></i>
                                        <p class="font-bold">Wilayah tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- ============================================== -->
        <!--         DRILL-DOWN MASTER DETAIL FLOW          -->
        <!-- ============================================== -->
        @if(!$selectedProvince)
            <!-- LEVEL 1: PROVINCES GRID -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-800 text-base uppercase tracking-wide">Daftar Provinsi</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Pilih Provinsi untuk melihat daftar Kota / Kabupaten</p>
                    </div>
                    @if($isAdmin)
                        <button onclick="openProvinceModal()" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-orange-600 text-white font-black rounded-2xl hover:bg-orange-500 transition-all shadow-md group text-xs uppercase tracking-wider">
                            <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i>
                            Tambah Provinsi
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($provinces as $prov)
                        @php 
                            $citiesCount = \App\Models\Area::where('province', $prov)->distinct()->count('city');
                            $provCode = \App\Models\Area::getProvinceCodeByName($prov);
                        @endphp
                        <div class="group relative flex flex-col bg-white rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                            <div class="p-6 flex flex-col items-center text-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-orange-50 group-hover:text-orange-600 flex items-center justify-center border border-slate-100 group-hover:border-orange-100 transition-all duration-300">
                                    <i data-lucide="globe" class="w-6 h-6"></i>
                                </div>

                                <h3 class="mt-4 font-black text-slate-800 text-sm uppercase tracking-tight truncate w-full px-2">{{ $prov }} ({{ $provCode }})</h3>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1.5">{{ $citiesCount }} KOTA / KAB</p>

                                @if($isAdmin)
                                    <div class="absolute top-4 right-4 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="event.preventDefault(); openProvinceModal('{{ $prov }}', '{{ $provCode }}')" class="p-1.5 bg-white text-slate-400 hover:text-orange-500 rounded-lg shadow-sm border border-slate-100 hover:scale-105 transition-all" title="Edit Provinsi">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button onclick="event.preventDefault(); openDeleteProvinceModal('{{ $prov }}')" class="p-1.5 bg-white text-slate-400 hover:text-red-500 rounded-lg shadow-sm border border-slate-100 hover:scale-105 transition-all" title="Hapus Provinsi">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('area.index', ['province' => $prov]) }}" 
                               class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 mt-auto flex items-center justify-between group-hover:bg-orange-500 transition-all duration-300">
                                <span class="text-[9px] font-black text-slate-400 group-hover:text-white uppercase tracking-widest">Buka Provinsi</span>
                                <div class="w-7 h-7 rounded-lg bg-white text-slate-300 group-hover:text-slate-900 flex items-center justify-center transition-all shadow-sm">
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-slate-100 flex flex-col items-center justify-center">
                            <i data-lucide="map" class="w-12 h-12 text-slate-200 mb-4"></i>
                            <h4 class="font-black text-slate-800 text-sm uppercase tracking-wide">Belum ada Provinsi</h4>
                            <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">Silakan daftarkan provinsi pertama Anda.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        @elseif($selectedProvince && !$selectedCity)
            <!-- LEVEL 2: CITIES GRID UNDER SELECTED PROVINCE -->
            <div class="space-y-6">
                <!-- Breadcrumbs & Nav Header -->
                <div class="bg-white rounded-3xl border border-slate-200/60 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('area.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-slate-800 transition-all active:scale-95 border border-slate-100 shadow-sm" title="Kembali ke Daftar Provinsi">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        </a>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">PROVINSI</span>
                                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                                <span class="text-xs font-black text-orange-600 uppercase tracking-wide">{{ $selectedProvince }} ({{ \App\Models\Area::getProvinceCodeByName($selectedProvince) }})</span>
                            </div>
                            <h3 class="font-black text-slate-800 text-base uppercase tracking-tight mt-1">Daftar Kota / Kabupaten</h3>
                        </div>
                    </div>
                    @if($isAdmin)
                        <button onclick="openCityModal('{{ $selectedProvince }}')" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-orange-600 text-white font-black rounded-2xl hover:bg-orange-500 transition-all shadow-md group text-xs uppercase tracking-wider">
                            <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i>
                            Tambah Kota
                        </button>
                    @endif
                </div>

                <!-- Cities Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($cities as $cit)
                        @php 
                            $kecCount = \App\Models\Area::where('province', $selectedProvince)->where('city', $cit)->count();
                            $cityCode = \App\Models\Area::getCityCodeByName($selectedProvince, $cit);
                        @endphp
                        <div class="group relative flex flex-col bg-white rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                            <div class="p-6 flex flex-col items-center text-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-orange-50 group-hover:text-orange-600 flex items-center justify-center border border-slate-100 group-hover:border-orange-100 transition-all duration-300">
                                    <i data-lucide="building-2" class="w-6 h-6"></i>
                                </div>

                                <h3 class="mt-4 font-black text-slate-800 text-sm truncate w-full px-2">{{ $cit }} ({{ $cityCode }})</h3>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1.5">{{ $kecCount }} KECAMATAN</p>

                                @if($isAdmin)
                                    <div class="absolute top-4 right-4 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="event.preventDefault(); openCityModal('{{ $selectedProvince }}', '{{ $cit }}', '{{ $cityCode }}')" class="p-1.5 bg-white text-slate-400 hover:text-orange-500 rounded-lg shadow-sm border border-slate-100 hover:scale-105 transition-all" title="Edit Kota">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button onclick="event.preventDefault(); openDeleteCityModal('{{ $selectedProvince }}', '{{ $cit }}')" class="p-1.5 bg-white text-slate-400 hover:text-red-500 rounded-lg shadow-sm border border-slate-100 hover:scale-105 transition-all" title="Hapus Kota">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('area.index', ['province' => $selectedProvince, 'city' => $cit]) }}" 
                               class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 mt-auto flex items-center justify-between group-hover:bg-orange-500 transition-all duration-300">
                                <span class="text-[9px] font-black text-slate-400 group-hover:text-white uppercase tracking-widest">Buka Kota</span>
                                <div class="w-7 h-7 rounded-lg bg-white text-slate-300 group-hover:text-slate-900 flex items-center justify-center transition-all shadow-sm">
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-slate-100 flex flex-col items-center justify-center">
                            <i data-lucide="map" class="w-12 h-12 text-slate-200 mb-4"></i>
                            <h4 class="font-black text-slate-800 text-sm uppercase tracking-wide">Belum ada Kota / Kabupaten</h4>
                            <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">Silakan daftarkan kota pertama di provinsi ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        @elseif($selectedProvince && $selectedCity)
            <!-- LEVEL 3: KECAMATANS TABLE VIEW UNDER SELECTED CITY -->
            <div class="space-y-6">
                <!-- Breadcrumbs & Nav Header -->
                <div class="bg-white rounded-3xl border border-slate-200/60 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('area.index', ['province' => $selectedProvince]) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-slate-800 transition-all active:scale-95 border border-slate-100 shadow-sm" title="Kembali ke Daftar Kota">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        </a>
                        <div>
                            <div class="flex items-center flex-wrap gap-2 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <a href="{{ route('area.index') }}" class="hover:text-orange-500">PROVINSI</a>
                                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                                <a href="{{ route('area.index', ['province' => $selectedProvince]) }}" class="hover:text-orange-500">{{ $selectedProvince }} ({{ \App\Models\Area::getProvinceCodeByName($selectedProvince) }})</a>
                                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                                <span class="text-orange-600">{{ $selectedCity }} ({{ \App\Models\Area::getCityCodeByName($selectedProvince, $selectedCity) }})</span>
                            </div>
                            <h3 class="font-black text-slate-800 text-base uppercase tracking-tight mt-1.5 flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-emerald-600"></i>
                                Kecamatan Operasional
                            </h3>
                        </div>
                    </div>
                    @if($isAdmin)
                        <button onclick="openKecamatanModal('{{ $selectedProvince }}', '{{ $selectedCity }}')" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-orange-600 text-white font-black rounded-2xl hover:bg-orange-500 transition-all shadow-md group text-xs uppercase tracking-wider">
                            <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i>
                            Tambah Kecamatan
                        </button>
                    @endif
                </div>

                <!-- Table View -->
                <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/50 border-b border-slate-100">
                                <tr>
                                    <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kecamatan</th>
                                    <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kode Kecamatan</th>
                                    <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kode Area Lengkap</th>
                                    @if($isAdmin)
                                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm font-bold text-slate-600">
                                @forelse($kecamatans as $kec)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-10 py-5 text-slate-800">{{ $kec->name }}</td>
                                        <td class="px-10 py-5 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-200">
                                                {{ $kec->kecamatan_code }}
                                            </span>
                                        </td>
                                        <td class="px-10 py-5 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-orange-50 text-orange-600 text-[10px] font-black uppercase tracking-widest border border-orange-100">
                                                {{ $kec->code }}
                                            </span>
                                        </td>
                                        @if($isAdmin)
                                            <td class="px-10 py-5">
                                                <div class="flex items-center justify-center gap-3">
                                                    <button onclick="openKecamatanModal('{{ $selectedProvince }}', '{{ $selectedCity }}', '{{ $kec->id }}', '{{ $kec->name }}', '{{ $kec->kecamatan_code }}')" 
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-orange-500 hover:bg-white border border-transparent hover:border-orange-100 transition-all hover:shadow-md" title="Edit Kecamatan">
                                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                                    </button>
                                                    <button onclick="openDeleteKecamatanModal('{{ $kec->id }}', '{{ $kec->name }}')" 
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-white border border-transparent hover:border-red-100 transition-all hover:shadow-md" title="Hapus Kecamatan">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-10 py-16 text-center text-slate-400">
                                            <div class="flex flex-col items-center">
                                                <i data-lucide="map" class="w-12 h-12 mb-4 opacity-20"></i>
                                                <p class="font-bold">Belum ada kecamatan di kota ini</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

@if($isAdmin)
    <!-- ============================================== -->
    <!--                   MODALS SECTION               -->
    <!-- ============================================== -->

    <!-- PROVINCE MODAL (ADD / EDIT) -->
    <div id="provinceModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/80 w-full max-w-md overflow-hidden transform scale-95 transition-transform">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 id="provinceModalTitle" class="font-black text-slate-800 text-sm uppercase tracking-wide">Tambah Provinsi Baru</h3>
                <button type="button" onclick="closeProvinceModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="provinceForm" action="{{ route('area.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="action_type" value="province">
                <input type="hidden" name="_method" id="provinceMethod" value="POST">
                <input type="hidden" name="old_province_name" id="oldProvinceName">

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2 space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Provinsi</label>
                        <input type="text" name="province_name" id="province_name" required
                            class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                    </div>
                    <div id="provinceCodeContainer" class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Prov</label>
                        <input type="text" name="province_code" id="province_code" required
                            class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-black text-orange-600 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all uppercase"
                            placeholder="ACE" maxlength="5">
                    </div>
                </div>

                <!-- Initial City/Kecamatan/Code (Only required/visible on CREATE province) -->
                <div id="provinceCreateSection" class="space-y-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2 space-y-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kota / Kabupaten Pertama</label>
                            <input type="text" name="city_name" id="province_city_name" 
                                class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all"
                                placeholder="Contoh: Lhokseumawe">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Kota</label>
                            <input type="text" name="city_code" id="province_city_code" 
                                class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-black text-orange-600 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all uppercase"
                                placeholder="LHK" maxlength="5">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2 space-y-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kecamatan Pertama</label>
                            <input type="text" name="kecamatan_name" id="province_kecamatan_name"
                                class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all"
                                placeholder="Contoh: Kuta Blang">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Kec</label>
                            <input type="text" name="kecamatan_code" id="province_kecamatan_code"
                                class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-black text-orange-600 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all uppercase"
                                placeholder="01" maxlength="5">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-black py-3 rounded-xl transition-all shadow-md text-xs uppercase tracking-wider">
                        Simpan
                    </button>
                    <button type="button" onclick="closeProvinceModal()" class="px-5 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 text-xs uppercase tracking-wider">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PROVINCE DELETE MODAL -->
    <div id="deleteProvinceModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden">
            <div class="p-6 text-center space-y-4">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto animate-pulse">
                    <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                </div>
                <div>
                    <h4 class="text-base font-black text-slate-800 uppercase tracking-wide">Hapus Provinsi?</h4>
                    <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">Anda yakin ingin menghapus Provinsi <span id="delProvName" class="font-black text-red-600"></span>? Semua kota dan kecamatan di dalamnya akan ikut terhapus secara permanen.</p>
                </div>
                <form action="{{ route('area.destroy', 0) }}" method="POST" class="flex items-center gap-3 pt-2">
                    @csrf @method('DELETE')
                    <input type="hidden" name="action_type" value="province">
                    <input type="hidden" name="province_name" id="delProvInput">
                    <button type="submit" class="flex-1 bg-red-600 text-white font-black py-3 rounded-xl hover:bg-red-500 transition-all text-xs uppercase tracking-wider">
                        Ya, Hapus Semua
                    </button>
                    <button type="button" onclick="closeDeleteProvinceModal()" class="flex-1 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 text-xs uppercase tracking-wider">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- CITY MODAL (ADD / EDIT) -->
    <div id="cityModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 id="cityModalTitle" class="font-black text-slate-800 text-sm uppercase tracking-wide">Tambah Kota Baru</h3>
                <button type="button" onclick="closeCityModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="cityForm" action="{{ route('area.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="action_type" value="city">
                <input type="hidden" name="province" id="cityProvince">
                <input type="hidden" name="_method" id="cityMethod" value="POST">
                <input type="hidden" name="old_city_name" id="oldCityName">

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2 space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Kota / Kabupaten</label>
                        <input type="text" name="city_name" id="city_name" required
                            class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                    </div>
                    <div id="cityCodeContainer" class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Kota</label>
                        <input type="text" name="city_code" id="city_code" required
                            class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-black text-orange-600 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all uppercase"
                            placeholder="LHK" maxlength="5">
                    </div>
                </div>

                <!-- Initial Kecamatan/Code (Only required/visible on CREATE city) -->
                <div id="cityCreateSection" class="space-y-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2 space-y-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kecamatan Pertama</label>
                            <input type="text" name="kecamatan_name" id="city_kecamatan_name"
                                class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all"
                                placeholder="Contoh: Kuta Blang">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Kec</label>
                            <input type="text" name="kecamatan_code" id="city_kecamatan_code"
                                class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-black text-orange-600 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all uppercase"
                                placeholder="01" maxlength="5">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-black py-3 rounded-xl transition-all shadow-md text-xs uppercase tracking-wider">
                        Simpan
                    </button>
                    <button type="button" onclick="closeCityModal()" class="px-5 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 text-xs uppercase tracking-wider">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CITY DELETE MODAL -->
    <div id="deleteCityModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden">
            <div class="p-6 text-center space-y-4">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto animate-pulse">
                    <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                </div>
                <div>
                    <h4 class="text-base font-black text-slate-800 uppercase tracking-wide">Hapus Kota / Kabupaten?</h4>
                    <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">Anda yakin ingin menghapus Kota <span id="delCityName" class="font-black text-red-600"></span>? Semua kecamatan operasional di dalamnya akan ikut terhapus secara permanen.</p>
                </div>
                <form action="{{ route('area.destroy', 0) }}" method="POST" class="flex items-center gap-3 pt-2">
                    @csrf @method('DELETE')
                    <input type="hidden" name="action_type" value="city">
                    <input type="hidden" name="province" id="delCityProvince">
                    <input type="hidden" name="city_name" id="delCityInput">
                    <button type="submit" class="flex-1 bg-red-600 text-white font-black py-3 rounded-xl hover:bg-red-500 transition-all text-xs uppercase tracking-wider">
                        Ya, Hapus Semua
                    </button>
                    <button type="button" onclick="closeDeleteCityModal()" class="flex-1 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 text-xs uppercase tracking-wider">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- KECAMATAN MODAL (ADD / EDIT) -->
    <div id="kecamatanModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 id="kecamatanModalTitle" class="font-black text-slate-800 text-sm uppercase tracking-wide">Tambah Kecamatan</h3>
                <button type="button" onclick="closeKecamatanModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="kecamatanForm" action="{{ route('area.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="action_type" value="kecamatan">
                <input type="hidden" name="province" id="kecProvince">
                <input type="hidden" name="city" id="kecCity">
                <input type="hidden" name="_method" id="kecMethod" value="POST">

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2 space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Kecamatan</label>
                        <input type="text" name="kecamatan_name" id="kecamatan_name" required
                            class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Kec</label>
                        <input type="text" name="kecamatan_code" id="kecamatan_code" required
                            class="w-full px-4 py-3 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-sm font-black text-orange-600 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all uppercase"
                            placeholder="01" maxlength="5">
                    </div>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-black py-3 rounded-xl transition-all shadow-md text-xs uppercase tracking-wider">
                        Simpan
                    </button>
                    <button type="button" onclick="closeKecamatanModal()" class="px-5 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 text-xs uppercase tracking-wider">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- KECAMATAN DELETE MODAL -->
    <div id="deleteKecamatanModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-sm overflow-hidden">
            <div class="p-6 text-center space-y-4">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="trash-2" class="w-8 h-8"></i>
                </div>
                <div>
                    <h4 class="text-base font-black text-slate-800 uppercase tracking-wide">Hapus Kecamatan?</h4>
                    <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">Anda yakin ingin menghapus Kecamatan <span id="delKecName" class="font-black text-red-600"></span>? Transaksi atau salesmen yang menggunakan kecamatan ini mungkin terpengaruh.</p>
                </div>
                <form id="deleteKecForm" action="{{ route('area.destroy', 0) }}" method="POST" class="flex items-center gap-3 pt-2">
                    @csrf @method('DELETE')
                    <input type="hidden" name="action_type" value="kecamatan">
                    <button type="submit" class="flex-1 bg-red-600 text-white font-black py-3 rounded-xl hover:bg-red-500 transition-all text-xs uppercase tracking-wider">
                        Hapus Kecamatan
                    </button>
                    <button type="button" onclick="closeDeleteKecamatanModal()" class="flex-1 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 text-xs uppercase tracking-wider">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
@if($isAdmin)
    // ==============================================
    //               PROVINCE MODAL SCRIPTS
    // ==============================================
    const pModal = document.getElementById('provinceModal');
    const pTitle = document.getElementById('provinceModalTitle');
    const pForm = document.getElementById('provinceForm');
    const pMethod = document.getElementById('provinceMethod');
    const pOldName = document.getElementById('oldProvinceName');
    const pNameInput = document.getElementById('province_name');
    const pCodeContainer = document.getElementById('provinceCodeContainer');
    const pCreateSec = document.getElementById('provinceCreateSection');

    function openProvinceModal(oldName = '', oldCode = '') {
        pModal.classList.remove('hidden');
        pModal.classList.add('flex');
        
        if (oldName) {
            pTitle.innerText = "Edit Provinsi";
            pForm.action = "{{ route('area.update', 0) }}";
            pMethod.value = "PUT";
            pOldName.value = oldName;
            pNameInput.value = oldName;
            pNameInput.name = 'new_province_name'; // Matches AreaController expectations
            
            document.getElementById('province_code').value = oldCode;
            pCodeContainer.classList.remove('hidden');
            pCreateSec.classList.add('hidden');
            
            document.getElementById('province_code').setAttribute('required', 'true');
            document.getElementById('province_city_name').removeAttribute('required');
            document.getElementById('province_city_code').removeAttribute('required');
            document.getElementById('province_kecamatan_name').removeAttribute('required');
            document.getElementById('province_kecamatan_code').removeAttribute('required');
        } else {
            pTitle.innerText = "Tambah Provinsi Baru";
            pForm.action = "{{ route('area.store') }}";
            pMethod.value = "POST";
            pOldName.value = "";
            pNameInput.value = "";
            pNameInput.name = 'province_name';
            
            document.getElementById('province_code').value = "";
            pCodeContainer.classList.remove('hidden');
            pCreateSec.classList.remove('hidden');
            
            document.getElementById('province_code').setAttribute('required', 'true');
            document.getElementById('province_city_name').setAttribute('required', 'true');
            document.getElementById('province_city_code').setAttribute('required', 'true');
            document.getElementById('province_kecamatan_name').setAttribute('required', 'true');
            document.getElementById('province_kecamatan_code').setAttribute('required', 'true');
        }
        lucide.createIcons();
    }

    function closeProvinceModal() {
        pModal.classList.remove('flex');
        pModal.classList.add('hidden');
    }

    // PROVINCE DELETE
    const dpModal = document.getElementById('deleteProvinceModal');
    const delProvName = document.getElementById('delProvName');
    const delProvInput = document.getElementById('delProvInput');

    function openDeleteProvinceModal(name) {
        dpModal.classList.remove('hidden');
        dpModal.classList.add('flex');
        delProvName.innerText = name.toUpperCase();
        delProvInput.value = name;
    }

    function closeDeleteProvinceModal() {
        dpModal.classList.remove('flex');
        dpModal.classList.add('hidden');
    }

    // ==============================================
    //                 CITY MODAL SCRIPTS
    // ==============================================
    const cModal = document.getElementById('cityModal');
    const cTitle = document.getElementById('cityModalTitle');
    const cForm = document.getElementById('cityForm');
    const cMethod = document.getElementById('cityMethod');
    const cProvince = document.getElementById('cityProvince');
    const cOldName = document.getElementById('oldCityName');
    const cNameInput = document.getElementById('city_name');
    const cCodeContainer = document.getElementById('cityCodeContainer');
    const cCreateSec = document.getElementById('cityCreateSection');

    function openCityModal(province, oldCityName = '', oldCityCode = '') {
        cModal.classList.remove('hidden');
        cModal.classList.add('flex');
        cProvince.value = province;
        
        if (oldCityName) {
            cTitle.innerText = `Edit Kota - ${province}`;
            cForm.action = "{{ route('area.update', 0) }}";
            cMethod.value = "PUT";
            cOldName.value = oldCityName;
            cNameInput.value = oldCityName;
            cNameInput.name = 'new_city_name'; // Matches AreaController expectations
            
            document.getElementById('city_code').value = oldCityCode;
            cCodeContainer.classList.remove('hidden');
            cCreateSec.classList.add('hidden');
            
            document.getElementById('city_code').setAttribute('required', 'true');
            document.getElementById('city_kecamatan_name').removeAttribute('required');
            document.getElementById('city_kecamatan_code').removeAttribute('required');
        } else {
            cTitle.innerText = `Tambah Kota Baru di ${province}`;
            cForm.action = "{{ route('area.store') }}";
            cMethod.value = "POST";
            cOldName.value = "";
            cNameInput.value = "";
            cNameInput.name = 'city_name';
            
            document.getElementById('city_code').value = "";
            cCodeContainer.classList.remove('hidden');
            cCreateSec.classList.remove('hidden');
            
            document.getElementById('city_code').setAttribute('required', 'true');
            document.getElementById('city_kecamatan_name').setAttribute('required', 'true');
            document.getElementById('city_kecamatan_code').setAttribute('required', 'true');
        }
        lucide.createIcons();
    }

    function closeCityModal() {
        cModal.classList.remove('flex');
        cModal.classList.add('hidden');
    }

    // CITY DELETE
    const dcModal = document.getElementById('deleteCityModal');
    const delCityName = document.getElementById('delCityName');
    const delCityProvince = document.getElementById('delCityProvince');
    const delCityInput = document.getElementById('delCityInput');

    function openDeleteCityModal(province, city) {
        dcModal.classList.remove('hidden');
        dcModal.classList.add('flex');
        delCityName.innerText = city;
        delCityProvince.value = province;
        delCityInput.value = city;
    }

    function closeDeleteCityModal() {
        dcModal.classList.remove('flex');
        dcModal.classList.add('hidden');
    }

    // ==============================================
    //              KECAMATAN MODAL SCRIPTS
    // ==============================================
    const kModal = document.getElementById('kecamatanModal');
    const kTitle = document.getElementById('kecamatanModalTitle');
    const kForm = document.getElementById('kecamatanForm');
    const kMethod = document.getElementById('kecMethod');
    const kProvince = document.getElementById('kecProvince');
    const kCity = document.getElementById('kecCity');
    const kNameInput = document.getElementById('kecamatan_name');
    const kCodeInput = document.getElementById('kecamatan_code');

    function openKecamatanModal(province, city, id = '', name = '', code = '') {
        kModal.classList.remove('hidden');
        kModal.classList.add('flex');
        kProvince.value = province;
        kCity.value = city;
        
        if (id) {
            kTitle.innerText = `Edit Kecamatan - ${city}`;
            kForm.action = `/area/${id}`;
            kMethod.value = "PUT";
            kNameInput.value = name;
            kCodeInput.value = code;
        } else {
            kTitle.innerText = `Tambah Kecamatan Baru di ${city}`;
            kForm.action = "{{ route('area.store') }}";
            kMethod.value = "POST";
            kNameInput.value = "";
            kCodeInput.value = "";
        }
        lucide.createIcons();
    }

    function closeKecamatanModal() {
        kModal.classList.remove('flex');
        kModal.classList.add('hidden');
    }

    // KECAMATAN DELETE
    const dkModal = document.getElementById('deleteKecamatanModal');
    const delKecName = document.getElementById('delKecName');
    const deleteKecForm = document.getElementById('deleteKecForm');

    function openDeleteKecamatanModal(id, name) {
        dkModal.classList.remove('hidden');
        dkModal.classList.add('flex');
        delKecName.innerText = name;
        deleteKecForm.action = `/area/${id}`;
    }

    function closeDeleteKecamatanModal() {
        dkModal.classList.remove('flex');
        dkModal.classList.add('hidden');
    }
@endif
</script>
@endpush
