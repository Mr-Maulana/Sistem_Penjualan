@extends('layouts.admin')

@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('page-title', 'User')
@section('page-subtitle', isset($user) ? 'Edit user' : 'Tambah user')

@section('content')
<div class="max-w-5xl mx-auto">
    <form method="POST" action="{{ isset($user) ? route('user.update', $user) : route('user.store') }}" class="space-y-6">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Account Settings -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm uppercase tracking-wider">
                            <i data-lucide="shield" class="w-4 h-4 text-indigo-500"></i>
                            Informasi Akun
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <input name="name" value="{{ old('name', $user->name ?? '') }}" 
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                       required placeholder="Nama Lengkap">
                            </div>
                            @error('name') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                       required placeholder="email@example.com">
                            </div>
                            @error('email') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Role</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i data-lucide="key" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <select name="role" class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50 appearance-none">
                                    @php($v = old('role', $user->role ?? 'sales'))
                                    @foreach($roles as $r)
                                        <option value="{{ $r }}" {{ $v === $r ? 'selected' : '' }}>{{ strtoupper($r) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('role') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Password {{ isset($user) ? '(Opsional)' : '' }}</label>
                            <div class="relative mb-4">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <input type="password" name="password" 
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                       {{ isset($user) ? '' : 'required' }} placeholder="••••••••">
                            </div>
                            @error('password') <div class="text-xs text-red-500 mt-1.5 font-medium mb-4">{{ $message }}</div> @enderror

                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <input type="password" name="password_confirmation" 
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                       {{ isset($user) ? '' : 'required' }} placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Personal Data -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm uppercase tracking-wider">
                            <i data-lucide="contact-2" class="w-4 h-4 text-emerald-500"></i>
                            Data Diri & Identitas
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">NIK (KTP)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i data-lucide="credit-card" class="w-4 h-4 text-slate-400"></i>
                                    </div>
                                    <input name="nik" value="{{ old('nik', $user->nik ?? '') }}" 
                                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                           placeholder="16 Digit NIK">
                                </div>
                                @error('nik') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">NIP (Pegawai)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i data-lucide="badge-id" class="w-4 h-4 text-slate-400"></i>
                                    </div>
                                    <input name="nip" value="{{ old('nip', $user->nip ?? '') }}" 
                                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                           placeholder="Nomor Induk Pegawai">
                                </div>
                                @error('nip') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Profesi / Jabatan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i data-lucide="briefcase" class="w-4 h-4 text-slate-400"></i>
                                    </div>
                                    <input name="profesi" value="{{ old('profesi', $user->profesi ?? '') }}" 
                                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                           placeholder="Contoh: Senior Accountant">
                                </div>
                                @error('profesi') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Nomor HP</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                                    </div>
                                    <input name="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                           placeholder="0812xxxx">
                                </div>
                                @error('phone') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Jenis Kelamin</label>
                                <div class="flex items-center gap-6 mt-3">
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="gender" value="L" {{ old('gender', $user->gender ?? '') === 'L' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                        <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Laki-laki</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="gender" value="P" {{ old('gender', $user->gender ?? '') === 'P' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                        <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Perempuan</span>
                                    </label>
                                </div>
                                @error('gender') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Tanggal Lahir</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                                    </div>
                                    <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date ?? '') }}" 
                                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50">
                                </div>
                                @error('birth_date') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Alamat Tinggal</label>
                                <textarea name="address" rows="3" 
                                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                                          placeholder="Alamat lengkap...">{{ old('address', $user->address ?? '') }}</textarea>
                                @error('address') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('user.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:text-slate-800 bg-white border border-slate-200 hover:border-slate-300 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl text-sm transition-all shadow-md hover:shadow-indigo-200 hover:-translate-y-0.5 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> 
                        {{ isset($user) ? 'Simpan Perubahan' : 'Daftarkan User' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
