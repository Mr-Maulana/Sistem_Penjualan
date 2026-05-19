@extends('layouts.admin')

@section('title', 'Detail Salesman')
@section('page-title', 'Detail Salesman')
@section('page-subtitle', 'Informasi detail tenaga penjual')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row gap-8 items-center lg:items-start">
            <!-- Profile Photo -->
            <div class="flex-shrink-0">
                <div class="w-32 h-32 lg:w-40 lg:h-40 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner">
                    @if($salesman->photo)
                        <img src="{{ asset('storage/' . $salesman->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300 text-4xl font-black">
                            {{ strtoupper(substr($salesman->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mb-4 justify-center lg:justify-start">
                    <h1 class="text-3xl font-black text-slate-900 leading-none">{{ $salesman->name }}</h1>
                    <div class="flex items-center gap-2 justify-center lg:justify-start">
                        <span class="px-3 py-1 rounded-full {{ $salesman->level == 'manager' ? 'bg-purple-100 text-purple-700 border-purple-200' : ($salesman->level == 'supervisor' ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-orange-100 text-orange-700 border-orange-200') }} text-[10px] font-black uppercase tracking-widest border">
                            {{ $salesman->level }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-200">
                            ID: {{ $salesman->code }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest border border-emerald-200">
                            {{ $salesman->area_display ?: 'Area: ' . $salesman->area }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-3 mb-8">
                    <p class="flex items-center justify-center lg:justify-start gap-2 text-slate-500 font-bold">
                        <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                        @if($salesman->area_display)
                            {{ $salesman->area_display }}
                        @else
                            {{ $salesman->city }} &bull; {{ $salesman->area }}
                        @endif
                    </p>
                    <p class="flex items-center justify-center lg:justify-start gap-2 text-slate-500 font-bold">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                        {{ $salesman->phone }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                    @can('update', $salesman)
                    <a href="{{ route('salesman.edit', $salesman) }}" class="px-6 py-2.5 bg-white border border-slate-200 hover:border-orange-500 hover:text-orange-600 text-slate-700 font-bold rounded-xl transition-all flex items-center gap-2 shadow-sm">
                        <i data-lucide="pencil" class="w-4 h-4 text-orange-500"></i> Edit Data
                    </a>
                    @endcan
                    <a href="{{ route('salesman.index') }}" class="px-6 py-2.5 bg-slate-50 text-slate-500 border border-slate-200 font-bold rounded-xl hover:bg-slate-100 transition-all flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                    </a>
                </div>
            </div>
            
            <!-- Account Status Side -->
            <div class="w-full lg:w-48 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Status Akun</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl {{ $salesman->status == 'active' ? 'bg-emerald-500' : 'bg-red-500' }} flex items-center justify-center text-white shadow-sm">
                        <i data-lucide="{{ $salesman->status == 'active' ? 'check' : 'x' }}" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-800 leading-none mb-1">{{ $salesman->status == 'active' ? 'Aktif' : 'Nonaktif' }}</p>
                        <p class="text-[10px] text-slate-500 font-medium">Sistem Berjalan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 lg:p-10 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Informasi Data Diri</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NIK (KTP)</p>
                        <p class="text-base font-bold text-slate-800">{{ $salesman->nik ?: '---' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NPWP</p>
                        <p class="text-base font-bold text-slate-800">{{ $salesman->npwp ?: '---' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email Perusahaan</p>
                        <p class="text-base font-bold text-slate-800 truncate">{{ $salesman->email ?: '---' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Telepon / WhatsApp</p>
                        <p class="text-base font-bold text-slate-800">{{ $salesman->phone }}</p>
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat Domisili</p>
                        <p class="text-base font-bold text-slate-700 leading-relaxed">{{ $salesman->address ?: 'Alamat belum diinput.' }}</p>
                    </div>
                </div>

                <div class="mt-10 pt-10 border-t border-slate-100 flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="shield-check" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-800 uppercase">Integrasi Sistem</h4>
                        <p class="text-xs text-slate-500 font-medium">Salesman ini memiliki hak akses sebagai <span class="font-bold text-blue-600 uppercase">{{ $salesman->level }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Hierarchy & Stats) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Target Card -->
            @if($salesman->level !== 'manager')
            <div class="bg-white rounded-3xl p-6 lg:p-8 border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-orange-500/5 rounded-full blur-2xl"></div>
                <div class="relative space-y-6">
                    <div class="flex justify-between items-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Target Bulanan</p>
                        <i data-lucide="target" class="w-5 h-5 text-orange-500"></i>
                    </div>
                    <h4 class="text-3xl font-black text-slate-900 tracking-tighter">Rp {{ number_format($salesman->target, 0, ',', '.') }}</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-black uppercase text-slate-400">
                            <span>Progress</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-500 w-1/2 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Team Section -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 lg:p-8 shadow-sm">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6">Struktur Organisasi</h3>
                
                @if($salesman->supervisor)
                <div class="mb-8">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Atasan Langsung</p>
                    <a href="{{ route('salesman.show', $salesman->supervisor) }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-orange-500 hover:bg-white transition-all group shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-200">
                            @if($salesman->supervisor->photo)
                                <img src="{{ asset('storage/' . $salesman->supervisor->photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i data-lucide="user" class="w-6 h-6"></i>
                                </div>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-black text-slate-800 truncate group-hover:text-orange-600 transition-colors">{{ $salesman->supervisor->name }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{ $salesman->supervisor->level }}</p>
                        </div>
                    </a>
                </div>
                @endif

                <div class="space-y-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Anggota Tim ({{ $salesman->subordinates->count() }})</p>
                    @forelse($salesman->subordinates->take(5) as $sub)
                    <a href="{{ route('salesman.show', $sub) }}" class="flex items-center justify-between p-3 rounded-2xl border border-transparent hover:border-slate-200 hover:bg-slate-50 transition-all group">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-9 h-9 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                @if($sub->photo)
                                    <img src="{{ asset('storage/' . $sub->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-bold text-slate-800 truncate group-hover:text-orange-600 transition-colors">{{ $sub->name }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">{{ $sub->city }}</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-orange-500 group-hover:translate-x-1 transition-all"></i>
                    </a>
                    @empty
                    <div class="py-10 text-center border-2 border-dashed border-slate-100 rounded-3xl">
                        <i data-lucide="users" class="w-8 h-8 text-slate-200 mx-auto mb-2"></i>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Tidak ada anggota</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
