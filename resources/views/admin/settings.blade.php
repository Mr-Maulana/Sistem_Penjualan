@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('page-subtitle', 'Konfigurasi parameter global dan kebijakan aplikasi')

@section('content')
<div class="space-y-6">
    <!-- Coming Soon Warning Alert -->
    <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute right-0 top-0 w-36 h-36 bg-white/10 rounded-full blur-2xl translate-x-10 -translate-y-10"></div>
        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                <i data-lucide="info" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-black text-sm uppercase tracking-wider">FITUR MENDATANG (COMING SOON)</h3>
                    <span class="px-2 py-0.5 bg-white/20 rounded text-[9px] font-black uppercase tracking-widest border border-white/10">v1.1</span>
                </div>
                <p class="text-xs text-white/90 font-bold mt-1.5 leading-relaxed">Halaman ini dirancang untuk mengonfigurasi parameter sistem secara langsung. Saat ini Anda dapat mengisi parameter simulasi di bawah ini untuk melihat pratinjau antarmuka.</p>
            </div>
        </div>
    </div>

    <!-- Main Form Settings -->
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-black text-slate-800 text-sm uppercase tracking-wide">Konfigurasi Aplikasi</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider">Parameter global sistem penjualan</p>
        </div>
        <form action="{{ route('admin.settings.save') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- App Name -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ config('app.name') }}" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                </div>

                <!-- Company Name -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Perusahaan</label>
                    <input type="text" name="company_name" value="PT. Maju Bersama" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email Perusahaan</label>
                    <input type="email" name="company_email" value="admin@majubersama.com" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                </div>

                <!-- Phone -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">No. Telepon Kantor</label>
                    <input type="text" name="company_phone" value="+62 21 555 1234" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                </div>

                <!-- Address -->
                <div class="col-span-full space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Kantor Pusat</label>
                    <textarea name="company_address" rows="3" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">Jl. Sudirman Kav. 21, Jakarta Selatan, DKI Jakarta</textarea>
                </div>

                <!-- Locale -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bahasa Default</label>
                    <select name="app_locale" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                        <option value="id" selected>Bahasa Indonesia</option>
                        <option value="en">English</option>
                    </select>
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
