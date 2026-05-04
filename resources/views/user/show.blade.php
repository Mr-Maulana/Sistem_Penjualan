@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'User')
@section('page-subtitle', 'Informasi lengkap pengguna sistem')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-10 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-500/10 rounded-full -ml-10 -mb-10 blur-2xl"></div>
            
            <div class="relative flex flex-col md:flex-row items-center gap-6">
                <div class="relative">
                @php($photo = $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) : null)
                    @if($photo)
                        <img src="{{ $photo }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white/10 shadow-xl">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-indigo-500 text-white flex items-center justify-center font-bold text-3xl ring-4 ring-white/10 shadow-xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white p-1.5 rounded-lg shadow-lg ring-4 ring-slate-800">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
                
                <div class="text-center md:text-left flex-1">
                    <h3 class="text-2xl font-black tracking-tight">{{ $user->name }}</h3>
                    <p class="text-slate-300 flex items-center justify-center md:justify-start gap-1.5 mt-1 text-sm">
                        <i data-lucide="mail" class="w-4 h-4"></i> {{ $user->email }}
                    </p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-4">
                        <span class="bg-white/10 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-widest flex items-center gap-1.5 border border-white/10">
                            <i data-lucide="shield" class="w-3.5 h-3.5 text-indigo-400"></i>
                            {{ $user->role }}
                        </span>
                        <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-widest flex items-center gap-1.5">
                            <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                            Active
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('user.edit', $user) }}" class="bg-white text-slate-900 hover:bg-slate-50 font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                        <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Profile
                    </a>
                    <a href="{{ route('user.index') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all border border-white/10 flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100">
            <div class="p-6 text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">User ID</p>
                <p class="font-mono text-slate-800 font-bold">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Registered On</p>
                <p class="text-slate-800 font-bold">{{ $user->created_at->format('d F Y') }}</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Last Update</p>
                <p class="text-slate-800 font-bold">{{ $user->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Data Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm uppercase tracking-wider">
                        <i data-lucide="user-square-2" class="w-4 h-4 text-indigo-500"></i>
                        Data Personal & Pekerjaan
                    </h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">NIK (KTP)</p>
                            <p class="text-slate-800 font-semibold flex items-center gap-2">
                                <i data-lucide="credit-card" class="w-4 h-4 text-slate-300"></i>
                                {{ $user->nik ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">NIP (Pegawai)</p>
                            <p class="text-slate-800 font-semibold flex items-center gap-2">
                                <i data-lucide="badge-id" class="w-4 h-4 text-slate-300"></i>
                                {{ $user->nip ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Profesi / Jabatan</p>
                            <p class="text-slate-800 font-semibold flex items-center gap-2">
                                <i data-lucide="briefcase" class="w-4 h-4 text-slate-300"></i>
                                {{ $user->profesi ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Jenis Kelamin</p>
                            <p class="text-slate-800 font-semibold flex items-center gap-2">
                                <i data-lucide="users-2" class="w-4 h-4 text-slate-300"></i>
                                {{ $user->gender == 'L' ? 'Laki-laki' : ($user->gender == 'P' ? 'Perempuan' : '-') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nomor HP</p>
                            <p class="text-slate-800 font-semibold flex items-center gap-2">
                                <i data-lucide="phone" class="w-4 h-4 text-slate-300"></i>
                                {{ $user->phone ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tanggal Lahir</p>
                            <p class="text-slate-800 font-semibold flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-slate-300"></i>
                                {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Lengkap</p>
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                <p class="text-slate-700 leading-relaxed italic">
                                    {{ $user->address ?? 'Alamat belum dilengkapi.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-200 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <h4 class="font-bold mb-4 flex items-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    Keamanan
                </h4>
                <div class="space-y-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-1">Status Password</p>
                        <p class="text-sm font-medium">Bcrypt Encrypted</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-1">Email Verification</p>
                        <p class="text-sm font-medium flex items-center gap-2">
                            @if($user->email_verified_at)
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i> Verified
                            @else
                                <i data-lucide="x-circle" class="w-4 h-4 text-indigo-300"></i> Unverified
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                    <i data-lucide="history" class="w-4 h-4 text-slate-400"></i>
                    Aktivitas
                </h4>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Account Created</p>
                            <p class="text-[10px] text-slate-500">{{ $user->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Last Profile Update</p>
                            <p class="text-[10px] text-slate-500">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
