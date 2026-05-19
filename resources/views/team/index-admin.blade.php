@extends('layouts.admin')

@section('title', 'Struktur Tim')
@section('page-title', 'Struktur Tim Sales')
@section('page-subtitle', 'Manajemen keseluruhan supervisor dan tim sales')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-10">
        <!-- Managers Section (Top Tier) -->
        @if($managers->count() > 0)
        <div class="space-y-6">
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-3 px-2">
                <span class="w-8 h-[2px] bg-purple-500 rounded-full"></span>
                <i data-lucide="crown" class="w-4 h-4 text-purple-600"></i> TOP LEVEL: MANAGERS
            </h3>
            
            @foreach($managers as $manager)
            <div class="relative pl-8">
                <!-- Vertical Line for Hierarchy -->
                <div class="absolute left-4 top-0 bottom-0 w-[2px] bg-slate-200"></div>
                
                <!-- Manager Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden mb-6 relative">
                    <div class="px-6 py-4 bg-purple-50/50 border-b border-slate-100 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center shadow-lg shadow-purple-500/30 ring-4 ring-white">
                                <i data-lucide="crown" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-800 text-lg">{{ $manager->name }}</h4>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-[9px] font-bold uppercase tracking-tighter">{{ $manager->area_display ?: ($manager->city ?? $manager->area) }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">#{{ $manager->code }}</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="window.dispatchEvent(new CustomEvent('open-force-modal', { detail: { id: {{ $manager->id }}, name: '{{ $manager->name }}' } }))" 
                                class="p-2.5 text-purple-600 hover:bg-purple-100 rounded-xl transition-all" title="Mutasi Manager">
                            <i data-lucide="arrow-right-left" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Nested Supervisors (Mid Tier) -->
                    <div class="p-6 bg-slate-50/30">
                        @if($manager->subordinates->count() > 0)
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($manager->subordinates as $supervisor)
                            <div class="relative pl-10">
                                <!-- Horizontal Connection Line -->
                                <div class="absolute left-0 top-6 w-10 h-[2px] bg-blue-300"></div>
                                
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                                    <div class="px-5 py-3 bg-blue-50/40 border-b border-slate-100 flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-500 text-white flex items-center justify-center shadow-md">
                                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-black text-slate-800">{{ $supervisor->name }}</h5>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase">{{ $supervisor->area_display ?: ($supervisor->city ?? $supervisor->area) }}</p>
                                            </div>
                                        </div>
                                        <button onclick="window.dispatchEvent(new CustomEvent('open-force-modal', { detail: { id: {{ $supervisor->id }}, name: '{{ $supervisor->name }}' } }))"
                                                class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-all">
                                            <i data-lucide="move" class="w-4 h-4"></i>
                                        </button>
                                    </div>

                                    <!-- Nested Sales (Bottom Tier) -->
                                    <div class="p-4 bg-white">
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                            @foreach($supervisor->subordinates as $sales)
                                            <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 group hover:border-orange-200 hover:bg-white transition-all">
                                                <div class="flex items-center gap-2 overflow-hidden">
                                                    <div class="w-7 h-7 rounded-full bg-white text-orange-500 flex items-center justify-center border border-slate-100 shadow-sm group-hover:bg-orange-500 group-hover:text-white transition-colors">
                                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                                    </div>
                                                    <div class="overflow-hidden">
                                                        <p class="text-[11px] font-bold text-slate-700 truncate">{{ $sales->name }}</p>
                                                        <p class="text-[8px] text-slate-400 truncate uppercase">{{ $sales->area_display ?: $sales->area }}</p>
                                                    </div>
                                                </div>
                                                <button onclick="window.dispatchEvent(new CustomEvent('open-force-modal', { detail: { id: {{ $sales->id }}, name: '{{ $sales->name }}' } }))"
                                                        class="text-slate-300 hover:text-orange-500 transition-colors ml-1">
                                                    <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                                                </button>
                                            </div>
                                            @endforeach
                                            @if($supervisor->subordinates->count() == 0)
                                            <p class="col-span-full text-[10px] text-slate-400 italic text-center py-2">Belum ada tim sales.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4 bg-white rounded-2xl border border-dashed border-slate-200">
                            <p class="text-xs text-slate-400 font-medium italic">Manager ini belum membawahi Supervisor.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Independent Supervisors (Parents without Top Tier) -->
        @if($supervisors->count() > 0)
        <div class="space-y-6">
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-3 px-2 mt-8">
                <span class="w-8 h-[2px] bg-blue-500 rounded-full"></span>
                <i data-lucide="shield-check" class="w-4 h-4 text-blue-600"></i> INDEPENDENT SUPERVISORS
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($supervisors as $supervisor)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden relative group hover:border-blue-300 transition-all">
                    <div class="px-6 py-4 bg-blue-50/50 border-b border-slate-100 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/20 ring-4 ring-white">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-800">{{ $supervisor->name }}</h4>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">{{ $supervisor->area_display ?: ($supervisor->city ?? $supervisor->area) }}</p>
                            </div>
                        </div>
                        <button onclick="window.dispatchEvent(new CustomEvent('open-force-modal', { detail: { id: {{ $supervisor->id }}, name: '{{ $supervisor->name }}' } }))"
                                class="p-2 text-blue-600 hover:bg-blue-100 rounded-xl transition-all">
                            <i data-lucide="arrow-right-left" class="w-4 h-4"></i>
                        </button>
                    </div>
                    
                    <div class="p-4 space-y-2">
                        @foreach($supervisor->subordinates as $sales)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50/80 border border-slate-100 hover:bg-white hover:border-orange-200 transition-all">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <div class="w-6 h-6 rounded-full bg-white text-orange-400 flex items-center justify-center border border-slate-100 shadow-sm">
                                    <i data-lucide="user" class="w-3 h-3"></i>
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 truncate">{{ $sales->name }}</span>
                            </div>
                            <button onclick="window.dispatchEvent(new CustomEvent('open-force-modal', { detail: { id: {{ $sales->id }}, name: '{{ $sales->name }}' } }))"
                                    class="text-slate-300 hover:text-orange-500 transition-colors">
                                <i data-lucide="move" class="w-3 h-3"></i>
                            </button>
                        </div>
                        @endforeach
                        @if($supervisor->subordinates->count() == 0)
                        <p class="text-[10px] text-slate-400 italic text-center py-2">Tim kosong.</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-amber-50 rounded-2xl shadow-sm border border-amber-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-100/50">
                <h4 class="font-bold text-amber-800">Sales Tanpa Supervisor</h4>
                <p class="text-xs text-amber-600">Perlu dialokasikan ke tim</p>
            </div>
            <div class="p-4">
                @forelse($unassignedSalesmen as $unassigned)
                <div class="flex items-center justify-between p-3 rounded-xl border border-amber-100 bg-white mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                            <i data-lucide="user-x" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">{{ $unassigned->name }}</p>
                            <p class="text-[10px] text-slate-400">{{ $unassigned->code }}</p>
                        </div>
                    </div>
                    <button onclick="window.dispatchEvent(new CustomEvent('open-force-modal', { detail: { id: {{ $unassigned->id }}, name: '{{ $unassigned->name }}' } }))" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Pindah Tim">
                        <i data-lucide="arrow-right-left" class="w-4 h-4"></i>
                    </button>
                </div>
                @empty
                <p class="text-sm text-amber-600 text-center py-4">Semua sales sudah memiliki tim.</p>
                @endforelse
            </div>
        </div>

        <a href="{{ route('team.approvals') }}" class="block w-full bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:border-blue-300 transition-colors group">
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Persetujuan Mutasi</h4>
                    <p class="text-xs text-slate-500 mt-1">Kelola pengajuan pindah tim</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-50 group-hover:bg-blue-50 flex items-center justify-center text-slate-400 group-hover:text-blue-500 transition-colors">
                    <i data-lucide="arrow-right-left" class="w-5 h-5"></i>
                </div>
            </div>
        </a>
    </div>
</div>

@push('modals')
<!-- Force Transfer Modal (Admin) using Alpine.js -->
<div x-data="{ show: false, id: '', name: '' }"
     @open-force-modal.window="show = true; id = $event.detail.id; name = $event.detail.name"
     x-show="show" x-cloak
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
     
    <!-- Overlay -->
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
         @click="show = false"></div>

    <!-- Modal Panel -->
    <div x-show="show"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 scale-90 translate-y-8"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden z-10">
        
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-blue-600 to-indigo-600">
            <h3 class="font-extrabold text-white text-lg">Pindah Tim Sales</h3>
            <button type="button" @click="show = false" class="text-blue-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-1.5 rounded-xl">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('team.force-transfer') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="salesman_id" x-model="id">
            
            <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-100/50">
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-1">Anggota yang dipindah:</p>
                <p x-text="name" class="font-black text-slate-800 text-xl"></p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Atasan Tujuan (Supervisor/Manager)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <select name="to_supervisor_id" class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-blue-500 transition-colors font-semibold text-slate-700 appearance-none">
                        <option value="">-- Cabut dari Tim (Tanpa Atasan) --</option>
                        @php
                            $allPotentialSupervisors = \App\Models\Salesman::whereIn('level', ['supervisor', 'manager'])->orderBy('level', 'desc')->orderBy('name')->get();
                        @endphp
                        @foreach($allPotentialSupervisors as $sup)
                            <option value="{{ $sup->id }}" 
                                data-city="{{ strtolower(trim($sup->city)) }}"
                                {{ old('supervisor_id', $salesman->supervisor_id ?? '') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }} [{{ strtoupper($sup->level) }} - {{ $sup->area_display ?: ($sup->city ?? $sup->area) }}]</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button type="button" @click="show = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all hover:scale-105 active:scale-95">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-md shadow-blue-500/30 transition-all hover:scale-105 hover:shadow-lg active:scale-95">Pindahkan Sekarang</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
