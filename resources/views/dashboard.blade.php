<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 tracking-tight flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="w-8 h-8 text-indigo-600"></i>
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        <!-- Welcome Section -->
        <div class="relative overflow-hidden bg-slate-900 rounded-[2rem] p-8 lg:p-12 text-white shadow-2xl shadow-indigo-100 group">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/30 transition-all duration-700"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-700"></div>
            
            <div class="relative flex flex-col md:flex-row items-center gap-8">
                <div class="flex-1 text-center md:text-left">
                    <span class="inline-block px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4 text-indigo-300">Statistik Terbaru</span>
                    <h1 class="text-3xl lg:text-5xl font-black mb-4 leading-tight">Selamat Datang Kembali, <br><span class="text-indigo-400">{{ Auth::user()->name }}</span>!</h1>
                    <p class="text-slate-400 max-w-md text-sm lg:text-base font-medium">Sistem penjualan Anda sudah diperbarui. Pantau performa bisnis Anda secara real-time dari sini.</p>
                </div>
                <div class="hidden md:block animate-float">
                    <div class="w-48 h-48 bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] flex items-center justify-center shadow-inner relative overflow-hidden">
                        <i data-lucide="trending-up" class="w-24 h-24 text-indigo-400/50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat Card 1 -->
            <div class="card-premium p-6 group hover:border-indigo-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-indigo-50 rounded-2xl group-hover:bg-indigo-600 transition-all duration-300">
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <span class="flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                        12%
                    </span>
                </div>
                <h3 class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1">Total Penjualan</h3>
                <p class="text-2xl font-black text-slate-800 tracking-tight">Rp 42.500.000</p>
            </div>

            <!-- Stat Card 2 -->
            <div class="card-premium p-6 group hover:border-blue-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-2xl group-hover:bg-blue-600 transition-all duration-300">
                        <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <span class="flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                        8%
                    </span>
                </div>
                <h3 class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1">Total Pesanan</h3>
                <p class="text-2xl font-black text-slate-800 tracking-tight">1,284</p>
            </div>

            <!-- Stat Card 3 -->
            <div class="card-premium p-6 group hover:border-emerald-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-emerald-50 rounded-2xl group-hover:bg-emerald-600 transition-all duration-300">
                        <i data-lucide="users" class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <span class="flex items-center gap-1 text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">
                        0%
                    </span>
                </div>
                <h3 class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1">Pelanggan Baru</h3>
                <p class="text-2xl font-black text-slate-800 tracking-tight">342</p>
            </div>

            <!-- Stat Card 4 -->
            <div class="card-premium p-6 group hover:border-amber-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-amber-50 rounded-2xl group-hover:bg-amber-600 transition-all duration-300">
                        <i data-lucide="package" class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <span class="flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-lg">
                        <i data-lucide="arrow-down-right" class="w-3 h-3"></i>
                        4%
                    </span>
                </div>
                <h3 class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1">Stok Produk</h3>
                <p class="text-2xl font-black text-slate-800 tracking-tight">24 Item</p>
            </div>
        </div>

        <!-- Recent Activity Table Placeholder -->
        <div class="card-premium p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Transaksi Terakhir</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Data 5 transaksi terbaru hari ini</p>
                </div>
                <button class="text-xs font-black text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1 uppercase tracking-widest bg-indigo-50 px-4 py-2 rounded-xl">
                    Lihat Semua
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
            
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="database" class="w-10 h-10 text-slate-200"></i>
                </div>
                <p class="text-slate-400 text-sm font-medium">Belum ada transaksi terekam untuk hari ini.</p>
            </div>
        </div>
    </div>
</x-app-layout>
