@extends('layouts.admin')

@section('title', 'User')
@section('page-title', 'User Management')
@section('page-subtitle', 'Kelola hak akses dan informasi akun pengguna sistem')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-slate-100 bg-white">
        <div>
            <h3 class="font-black text-slate-800 text-xl tracking-tight">Daftar Pengguna</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Total {{ $users->count() }} pengguna terdaftar dalam sistem</p>
        </div>
        <a href="{{ route('user.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl flex items-center gap-2 transition-all shadow-md shadow-indigo-100 hover:-translate-y-0.5 active:scale-95">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah User Baru
        </a>
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
                                    <i data-lucide="hash" class="w-3 h-3"></i> NIP: {{ $u->nip ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-sm font-semibold text-slate-600">{{ $u->email }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $u->phone ?? 'No Phone' }}</div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $u->role == 'admin' ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500/20' : ($u->role == 'supervisor' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20') }}">
                            <span class="w-1 h-1 rounded-full {{ $u->role == 'admin' ? 'bg-indigo-600' : ($u->role == 'supervisor' ? 'bg-emerald-600' : 'bg-blue-600') }}"></span>
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
                                <i data-lucide="users" class="w-10 h-10 text-slate-200"></i>
                            </div>
                            <h4 class="font-black text-slate-800 tracking-tight">Belum Ada User</h4>
                            <p class="text-xs text-slate-400 mt-1 max-w-[200px] leading-relaxed">Mulai bangun tim Anda dengan menambahkan pengguna baru ke sistem.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
