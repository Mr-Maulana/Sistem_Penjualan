@extends('layouts.admin')

@section('title', 'Manajemen Wilayah')
@section('page-title', 'Struktur Organisasi Wilayah')
@section('page-subtitle', 'Monitoring Supervisor dan Sales di bawah naungan Anda')

@section('content')
<div class="space-y-6">
    @if($pendingApprovalsCount > 0)
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl p-6 text-white shadow-lg shadow-orange-500/30 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                <i data-lucide="bell-ring" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-xl font-black">Persetujuan Mutasi Tertunda</h3>
                <p class="text-amber-50 text-sm opacity-90 mt-1">Ada {{ $pendingApprovalsCount }} pengajuan mutasi dari tim Anda yang menunggu ACC.</p>
            </div>
        </div>
        <a href="{{ route('team.approvals') }}" class="px-6 py-3 bg-white text-orange-600 hover:bg-orange-50 font-bold rounded-xl transition-colors shadow-sm">
            Lihat & Proses ACC
        </a>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-3xl p-6 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider mb-1">Level Anda</p>
            <h3 class="text-2xl font-black mb-4">Manager (Provinsi)</h3>
            <div class="flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-indigo-200"></i>
                <span class="text-sm font-semibold">{{ $me->area_display ?: $me->area }}</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Supervisor</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $supervisors->count() }}</h3>
            <p class="text-[10px] text-slate-500 mt-2 flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3 h-3"></i>
                Aktif mengelola kota
            </p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Sales</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $supervisors->sum(fn($s) => $s->subordinates->count()) }}</h3>
            <p class="text-[10px] text-slate-500 mt-2 flex items-center gap-1">
                <i data-lucide="users" class="w-3 h-3"></i>
                Aktif di lapangan
            </p>
        </div>
    </div>

    <!-- Hierarchy Tree View -->
    <div class="space-y-6">
        @forelse($supervisors as $supervisor)
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <!-- Supervisor Header -->
            <div class="px-8 py-5 bg-slate-50/80 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 text-lg leading-tight">{{ $supervisor->name }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-bold bg-blue-600 text-white px-2 py-0.5 rounded uppercase tracking-tighter">Supervisor</span>
                            <span class="text-[11px] text-slate-500 font-medium flex items-center gap-1">
                                <i data-lucide="map" class="w-3 h-3"></i> {{ $supervisor->area_display ?: $supervisor->area }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4 md:gap-6 mt-4 md:mt-0">
                    <button onclick="window.dispatchEvent(new CustomEvent('open-transfer-modal', { detail: { id: {{ $supervisor->id }}, name: '{{ $supervisor->name }} (Supervisor)' } }))" class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors flex items-center gap-1.5">
                        <i data-lucide="arrow-right-left" class="w-3.5 h-3.5"></i> Mutasi
                    </button>
                    <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tim Sales</p>
                        <p class="font-black text-slate-700">{{ $supervisor->subordinates->count() }} Orang</p>
                    </div>
                    <div class="text-right border-l border-slate-200 pl-4 md:pl-6">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target Tim</p>
                        <p class="font-black text-emerald-600 font-mono text-sm">Rp {{ number_format($supervisor->subordinates->sum('target'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Sales List (Subordinates) -->
            <div class="p-6">
                @if($supervisor->subordinates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($supervisor->subordinates as $sales)
                    <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/30 hover:bg-white hover:border-blue-200 hover:shadow-md hover:shadow-blue-500/5 transition-all duration-300 group">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-white text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 flex items-center justify-center border border-slate-100 group-hover:border-blue-100 transition-colors shadow-sm">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black text-slate-800 truncate">{{ $sales->name }}</p>
                                <p class="text-[10px] text-slate-500 font-medium uppercase tracking-tight">{{ $sales->area_display ?: ($sales->city . ' - ' . $sales->area) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] border-t border-slate-100 pt-3">
                            <div class="text-slate-500">
                                <p class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Target</p>
                                <p class="font-bold">Rp {{ number_format($sales->target, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button onclick="window.dispatchEvent(new CustomEvent('open-transfer-modal', { detail: { id: {{ $sales->id }}, name: '{{ $sales->name }} (Sales)' } }))" class="px-2.5 py-1.5 text-[9px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors uppercase tracking-wider flex items-center gap-1.5">
                                    <i data-lucide="arrow-right-left" class="w-3 h-3"></i> Mutasi
                                </button>
                                <div class="h-6 w-px bg-slate-100 hidden sm:block"></div>
                                <div class="text-right">
                                    <p class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Kontak</p>
                                    <p class="font-mono">{{ $sales->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-8 text-slate-400 opacity-60">
                    <i data-lucide="users" class="w-12 h-12 mb-3 stroke-[1.5]"></i>
                    <p class="text-sm font-semibold italic">Belum ada tim sales di bawah supervisor ini.</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-slate-200">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <i data-lucide="shield-alert" class="w-10 h-10"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-800">Belum ada Supervisor</h4>
            <p class="text-slate-500 max-w-sm mx-auto mt-2 italic">Data supervisor di bawah naungan manager di area ini belum terdata.</p>
        </div>
        @endforelse
    </div>

    <!-- Riwayat Pengajuan Mutasi dari Manager -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h4 class="font-bold text-slate-800">Riwayat Pengajuan Mutasi (Oleh Anda)</h4>
                <p class="text-xs text-slate-500 mt-1">Status mutasi yang Anda ajukan dan sedang diproses Admin</p>
            </div>
            <div class="p-6">
                @forelse($pendingTransfers as $transfer)
                <div class="p-4 rounded-2xl border border-amber-100 bg-amber-50/50 mb-3 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-bold text-slate-800">{{ $transfer->salesman->name }}</p>
                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider bg-amber-200 text-amber-800 rounded-md">Pending ACC Admin</span>
                        </div>
                        <p class="text-xs text-slate-600">Tujuan: <span class="font-semibold">{{ $transfer->toSupervisor->name ?? 'Keluar Dari Tim (Tanpa Atasan)' }}</span></p>
                        <p class="text-[10px] text-slate-500 mt-1 italic">"{{ $transfer->reason }}"</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-500 text-center py-4 italic">Tidak ada pengajuan mutasi yang sedang menunggu proses Admin.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Transfer Request Modal (Manager) -->
<div x-data="{ show: false, id: '', name: '' }"
     @open-transfer-modal.window="show = true; id = $event.detail.id; name = $event.detail.name"
     x-show="show" x-cloak
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
     
    <div x-show="show"
         x-transition.opacity
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
         @click="show = false"></div>

    <div x-show="show"
         x-transition
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
                <p x-text="name" class="font-black text-slate-800 text-lg"></p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Manager / Supervisor Tujuan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <select name="to_supervisor_id" required class="w-full pl-12 pr-4 py-3 bg-slate-50 border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-blue-500 transition-colors font-semibold text-slate-700 appearance-none">
                        <option value="">-- Pilih Tujuan --</option>
                        <option value="leave">-- Keluar Dari Tim (Tanpa Atasan) --</option>
                        @foreach($otherSupervisors as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }} ({{ ucfirst($sup->level) }}) [{{ $sup->area_display ?: ($sup->city ?? $sup->area) }}]</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Alasan Pemindahan</label>
                <textarea name="reason" rows="3" required class="w-full p-4 bg-slate-50 border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-blue-500 transition-colors text-sm" placeholder="Jelaskan alasan ringkas mengapa anggota ini dipindahkan..."></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-all hover:-translate-y-0.5 shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                <i data-lucide="send" class="w-5 h-5"></i> Ajukan Mutasi
            </button>
        </form>
    </div>
</div>
@endpush
@endsection
