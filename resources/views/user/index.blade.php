@extends('layouts.admin')

@section('title', 'User')
@section('page-title', 'User Management')
@section('page-subtitle', 'Kelola hak akses dan informasi akun pengguna sistem')

@section('content')
@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 font-medium text-sm flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-black text-slate-800 text-xl tracking-tight">Daftar Pengguna</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Total {{ $users->count() }} pengguna terdaftar dalam sistem</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.dispatchEvent(new CustomEvent('open-salesman-modal'))" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-md shadow-purple-100 hover:-translate-y-0.5 active:scale-95">
                <i data-lucide="user-check" class="w-4 h-4"></i> Tambah Akun Salesman
            </button>
            <a href="{{ route('user.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-md shadow-indigo-100 hover:-translate-y-0.5 active:scale-95">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah User Baru
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="px-8 py-4 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
        <form action="{{ route('user.index') }}" method="GET" class="w-full">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="block w-full pl-10 pr-24 py-2.5 bg-white border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-700"
                       placeholder="Cari user berdasarkan nama, email, nik, telepon, atau role...">
                <div class="absolute inset-y-0 right-0 pr-1.5 flex items-center gap-1.5">
                    @if(request('search'))
                        <a href="{{ route('user.index') }}" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all uppercase tracking-wider">
                            Reset
                        </a>
                    @endif
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-1.5 rounded-lg transition-all shadow-sm">
                        Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto text-slate-700">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400 text-[10px] uppercase font-black tracking-[0.1em]">
                    <th class="px-8 py-4 font-black">Informasi Pengguna</th>
                    <th class="px-8 py-4 font-black">Email & Kontak</th>
                    <th class="px-8 py-4 font-black">Role / Jabatan</th>
                    <th class="px-8 py-4 font-black text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $u)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            @php($photo = $u->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($u->profile_photo_path) : null)
                            @if($photo)
                                <img src="{{ $photo }}" alt="{{ $u->name }}" class="w-11 h-11 rounded-xl object-cover ring-1 ring-indigo-100 shadow-sm transition-all duration-300">
                            @else
                                <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm ring-1 ring-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $u->name }}</div>
                                <div class="text-[10px] text-slate-400 font-bold mt-0.5 flex items-center gap-1.5 uppercase tracking-wider">
                                    <i data-lucide="credit-card" class="w-3 h-3 text-slate-400"></i> NIK: {{ $u->nik ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-sm font-semibold text-slate-600">{{ $u->email }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $u->phone ?? 'No Phone' }}</div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $u->role == 'admin' ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500/20' : ($u->role == 'manager' ? 'bg-purple-50 text-purple-700 ring-1 ring-purple-600/20' : ($u->role == 'supervisor' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20')) }}">
                            <span class="w-1 h-1 rounded-full {{ $u->role == 'admin' ? 'bg-indigo-600' : ($u->role == 'manager' ? 'bg-purple-600' : ($u->role == 'supervisor' ? 'bg-emerald-600' : 'bg-blue-600')) }}"></span>
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('user.show', $u) }}" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white hover:shadow-sm text-slate-400 hover:text-indigo-600 transition-all border border-transparent hover:border-slate-100" title="Detail">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('user.edit', $u) }}" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white hover:shadow-sm text-slate-400 hover:text-blue-600 transition-all border border-transparent hover:border-slate-100" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            @if(auth()->id() !== $u->id)
                            <form action="{{ route('user.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white hover:shadow-sm text-slate-400 hover:text-red-600 transition-all border border-transparent hover:border-slate-100" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-5 ring-1 ring-slate-100">
                                <i data-lucide="{{ request('search') ? 'search' : 'users' }}" class="w-10 h-10 text-slate-300"></i>
                            </div>
                            @if(request('search'))
                                <h4 class="font-black text-slate-800 tracking-tight">Tidak Ada Hasil</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs leading-relaxed mb-4">Pencarian untuk "{{ request('search') }}" tidak menemukan kecocokan.</p>
                                <a href="{{ route('user.index') }}" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold rounded-xl transition-all shadow-sm">
                                    Reset Pencarian
                                </a>
                            @else
                                <h4 class="font-black text-slate-800 tracking-tight">Belum Ada User</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-[200px] leading-relaxed">Mulai bangun tim Anda dengan menambahkan pengguna baru ke sistem.</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('modals')
<!-- Create Account for Salesman Modal (Admin) using Alpine.js -->
<div x-data="{ show: false }"
     @open-salesman-modal.window="show = true"
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
        
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-purple-600 to-indigo-600">
            <div class="flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-white"></i>
                <h3 class="font-extrabold text-white text-lg">Buat Akun Salesman</h3>
            </div>
            <button type="button" @click="show = false" class="text-purple-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-1.5 rounded-xl">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('user.store-salesman') }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Pilih Salesman (Belum Berakun)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <select name="salesman_id" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-700 appearance-none">
                        <option value="">-- Pilih Salesman --</option>
                        @foreach($unlinkedSalesmen as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->name }} [{{ strtoupper($s->level) }} - {{ $s->code }}]
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Nama, email, telepon, dan alamat akan diambil otomatis dari data salesman.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Password Akun</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-700">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </div>
                    <input type="password" name="password_confirmation" required minlength="8" placeholder="Konfirmasi password"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-700">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" @click="show = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all hover:scale-105 active:scale-95">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 rounded-xl shadow-md shadow-purple-500/30 transition-all hover:scale-105 hover:shadow-lg active:scale-95">Buat Akun Sekarang</button>
            </div>
        </form>
    </div>
</div>
@endpush
