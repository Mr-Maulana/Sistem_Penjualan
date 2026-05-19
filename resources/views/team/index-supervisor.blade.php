@extends('layouts.admin')

@section('title', 'Tim Saya')
@section('page-title', 'Tim Sales Saya')
@section('page-subtitle', 'Manajemen anggota tim dan pengajuan mutasi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Anggota Tim</h4>
                        <p class="text-xs text-slate-500">Sales di bawah supervisi Anda</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @if($me->supervisor)
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-purple-50 border border-purple-100 rounded-xl">
                        <i data-lucide="crown" class="w-3.5 h-3.5 text-purple-600"></i>
                        <span class="text-[10px] font-bold text-purple-700 uppercase tracking-tight">Manager: {{ $me->supervisor->name }}</span>
                    </div>
                    @endif
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                        {{ $team->count() }} Anggota
                    </span>
                </div>
            </div>
            <div class="p-4">
                @if($team->count() > 0)
                <div class="space-y-3">
                    @foreach($team as $member)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:border-blue-200 transition-colors bg-white">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $member->name }}</p>
                                <p class="text-[10px] text-slate-500 font-medium tracking-tight"><span class="text-slate-700 font-bold uppercase">{{ $member->area_display ?: ($member->city . ' | ' . $member->area) }}</span></p>
                                <p class="text-[10px] text-slate-400 mt-0.5 italic">Telp: {{ $member->phone ?? '-' }}</p>
                            </div>
                        </div>
                        <button onclick="window.dispatchEvent(new CustomEvent('open-transfer-modal', { detail: { id: {{ $member->id }}, name: '{{ $member->name }}' } }))" class="px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            Mutasi
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                        <i data-lucide="users" class="w-8 h-8"></i>
                    </div>
                    <p class="text-sm text-slate-500">Belum ada anggota tim yang ditugaskan kepada Anda.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h4 class="font-bold text-slate-800">Riwayat Pengajuan Mutasi</h4>
                <p class="text-xs text-slate-500 mt-1">Status mutasi yang sedang diproses</p>
            </div>
            <div class="p-4">
                @forelse($pendingTransfers as $transfer)
                <div class="p-3 rounded-xl border border-amber-100 bg-amber-50 mb-3">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-sm font-bold text-slate-800">{{ $transfer->salesman->name }}</p>
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-amber-200 text-amber-800 rounded-md">Pending</span>
                    </div>
                    <div class="text-xs text-slate-600 space-y-1">
                        <p>Tujuan: <span class="font-semibold">{{ $transfer->toSupervisor->name }}</span></p>
                        <p class="text-slate-500 italic">"{{ $transfer->reason }}"</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-500 text-center py-4">Tidak ada pengajuan mutasi aktif.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Transfer Request Modal (Supervisor) using Alpine.js -->
<div x-data="{ show: false, id: '', name: '' }"
     @open-transfer-modal.window="show = true; id = $event.detail.id; name = $event.detail.name"
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
            <h3 class="font-extrabold text-white text-lg">Pengajuan Mutasi Anggota</h3>
            <button type="button" @click="show = false" class="text-blue-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-1.5 rounded-xl">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('team.transfer') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="salesman_id" x-model="id">
            
            <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-100/50">
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-1">Anggota yang dipindah:</p>
                <p x-text="name" class="font-black text-slate-800 text-xl"></p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Supervisor Tujuan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <select name="to_supervisor_id" required class="w-full pl-12 pr-4 py-3 bg-slate-50 border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-blue-500 transition-colors font-semibold text-slate-700 appearance-none">
                        <option value="">-- Pilih Supervisor Tujuan --</option>
                        @foreach($otherSupervisors as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Alasan Mutasi</label>
                <textarea name="reason" rows="3" required class="w-full p-4 bg-slate-50 border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-blue-500 transition-colors font-medium text-slate-700 placeholder-slate-400" placeholder="Jelaskan alasan pengajuan pemindahan..."></textarea>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" @click="show = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all hover:scale-105 active:scale-95">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-md shadow-blue-500/30 transition-all hover:scale-105 hover:shadow-lg active:scale-95">Ajukan Mutasi</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
