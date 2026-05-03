@extends('layouts.admin')

@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('page-title', 'User')
@section('page-subtitle', isset($user) ? 'Edit user' : 'Tambah user')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">{{ isset($user) ? 'Edit User' : 'Tambah User' }}</h3>
        </div>
        <form method="POST" action="{{ isset($user) ? route('user.update', $user) : route('user.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Nama</label>
                    <input name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('name') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Role</label>
                    <select name="role" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @php($v = old('role', $user->role ?? 'sales'))
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ $v === $r ? 'selected' : '' }}>{{ strtoupper($r) }}</option>
                        @endforeach
                    </select>
                    @error('role') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('email') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Password {{ isset($user) ? '(kosongkan jika tidak ganti)' : '' }}</label>
                    <input type="password" name="password" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('password') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('user.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

