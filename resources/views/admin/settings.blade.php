@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('page-subtitle', 'Kustomisasi identitas aplikasi, tema warna, dan latar belakang login')

@section('content')
<div class="space-y-6">
    <!-- Active Customizer Notice Banner -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute right-0 top-0 w-36 h-36 bg-white/10 rounded-full blur-2xl translate-x-10 -translate-y-10"></div>
        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                <i data-lucide="palette" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-black text-sm uppercase tracking-wider">KUSTOMISASI IDENTITAS AKTIF</h3>
                    <span class="px-2 py-0.5 bg-white/20 rounded text-[9px] font-black uppercase tracking-widest border border-white/10">v2.0</span>
                </div>
                <p class="text-xs text-white/90 font-bold mt-1.5 leading-relaxed">Anda sekarang dapat mengubah logo aplikasi, tema warna global, gambar latar belakang login, serta data instansi perusahaan Anda secara instan dan real-time.</p>
            </div>
        </div>
    </div>

    <!-- Main Form Settings -->
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-black text-slate-800 text-sm uppercase tracking-wide">Konfigurasi Aplikasi & Kustomisasi Visual</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider">Ubah visual identitas dan data instansi perusahaan Anda</p>
        </div>
        
        <form action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- App Name -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ \App\Helpers\SettingsHelper::get('app_name', config('app.name')) }}" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                </div>

                <!-- Company Name -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Perusahaan</label>
                    <input type="text" name="company_name" value="{{ \App\Helpers\SettingsHelper::get('company_name', 'PT. Maju Bersama') }}" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email Perusahaan</label>
                    <input type="email" name="company_email" value="{{ \App\Helpers\SettingsHelper::get('company_email', 'admin@majubersama.com') }}" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                </div>

                <!-- Phone -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">No. Telepon Kantor</label>
                    <input type="text" name="company_phone" value="{{ \App\Helpers\SettingsHelper::get('company_phone', '+62 21 555 1234') }}" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                </div>

                <!-- Address -->
                <div class="col-span-full space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Kantor Pusat</label>
                    <textarea name="company_address" rows="3" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">{{ \App\Helpers\SettingsHelper::get('company_address', 'Jl. Sudirman Kav. 21, Jakarta Selatan, DKI Jakarta') }}</textarea>
                </div>

                <!-- Theme Customizer -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tema Warna Aplikasi</label>
                    @php($currentTheme = \App\Helpers\SettingsHelper::get('theme', 'indigo'))
                    <select name="theme" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                        <option value="indigo" {{ $currentTheme === 'indigo' ? 'selected' : '' }}>Indigo / Default Blue-Indigo</option>
                        <option value="emerald" {{ $currentTheme === 'emerald' ? 'selected' : '' }}>Emerald / Sleek Green</option>
                        <option value="blue" {{ $currentTheme === 'blue' ? 'selected' : '' }}>Blue / Premium Clean Blue</option>
                        <option value="amber" {{ $currentTheme === 'amber' ? 'selected' : '' }}>Amber / Golden Warm</option>
                        <option value="rose" {{ $currentTheme === 'rose' ? 'selected' : '' }}>Rose / Crimson Red</option>
                        <option value="slate" {{ $currentTheme === 'slate' ? 'selected' : '' }}>Slate / Dark Luxury</option>
                    </select>
                </div>

                <!-- Locale -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bahasa Default</label>
                    <select name="app_locale" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                        <option value="id" {{ \App\Helpers\SettingsHelper::get('app_locale') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                        <option value="en" {{ \App\Helpers\SettingsHelper::get('app_locale') === 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>

                <!-- Dark Mode Config -->
                <div class="space-y-1.5 flex flex-col justify-center">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Mode Tampilan</label>
                    <label class="inline-flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="dark_mode" value="1" {{ \App\Helpers\SettingsHelper::get('dark_mode', false) ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/50 transition-all">
                        <span class="text-xs text-slate-700 font-bold uppercase tracking-wider group-hover:text-slate-900 transition-colors">Aktifkan Mode Gelap (Dark Mode)</span>
                    </label>
                </div>

                <!-- Logo Customizer -->
                <div class="space-y-3 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Logo Aplikasi (Max 2MB)</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center shadow-sm overflow-hidden">
                            <img src="{{ \App\Helpers\SettingsHelper::logoUrl() }}" alt="Current Logo" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="logo" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Login Background Customizer -->
                <div class="space-y-3 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Latar Belakang Login (Max 5MB)</label>
                    <div class="flex items-center gap-4">
                        <div class="w-24 h-16 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center shadow-sm overflow-hidden">
                            <img src="{{ \App\Helpers\SettingsHelper::loginBgUrl() }}" alt="Current BG" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="login_bg" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-2xl text-xs uppercase tracking-widest hover:bg-slate-800 transition-all shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
