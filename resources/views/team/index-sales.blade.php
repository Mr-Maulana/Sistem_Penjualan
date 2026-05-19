@extends('layouts.admin')

@section('title', 'Tim Saya')
@section('page-title', 'Tim Sales Saya')
@section('page-subtitle', 'Informasi supervisor dan rekan satu tim')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-sm overflow-hidden text-white relative">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="px-6 py-5 border-b border-white/10 relative z-10">
                <h4 class="font-bold">Supervisor Anda</h4>
            </div>
            <div class="p-6 relative z-10">
                @if($supervisor)
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-white backdrop-blur-sm border border-white/30">
                        <i data-lucide="shield-check" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <p class="text-xl font-bold">{{ $supervisor->name }}</p>
                        <p class="text-blue-100 text-sm mt-1"><i data-lucide="phone" class="w-3 h-3 inline"></i> {{ $supervisor->phone ?? '-' }}</p>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <i data-lucide="user-x" class="w-12 h-12 mx-auto mb-3 text-white/50"></i>
                    <p class="font-medium">Anda belum ditugaskan ke dalam tim manapun.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Rekan Kerja</h4>
                        <p class="text-xs text-slate-500">Anggota tim di bawah supervisor yang sama</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                    {{ $colleagues->count() }} Anggota
                </span>
            </div>
            <div class="p-4">
                @if($colleagues->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($colleagues as $member)
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-indigo-200 transition-colors bg-slate-50/50 hover:bg-indigo-50/30">
                        <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 border border-slate-100">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $member->name }}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $member->area_display ?: 'Area: ' . ($member->area ?? '-') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-sm text-slate-500">Tidak ada rekan kerja lain di tim ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
