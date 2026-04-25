{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Penjualan')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full bg-slate-100">
    <div class="h-full w-full flex overflow-hidden">
        {{-- Sidebar --}}
        <aside class="w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-white flex flex-col flex-shrink-0 h-full">
            <div class="p-5 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center font-bold text-lg">S</div>
                    <div>
                        <div class="font-bold text-sm leading-tight">Sistem Penjualan</div>
                        <div class="text-xs text-slate-400">PT. Maju Bersama</div>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 py-4 overflow-auto">
                <div class="px-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">Dashboard</div>
                <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="layout-dashboard" style="width:18px;height:18px;"></i> Dashboard
                </a>
                
                <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">Master Data</div>
                <a href="{{ route('distributor.index') }}" class="sidebar-item {{ request()->routeIs('distributor.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="building-2" style="width:18px;height:18px;"></i> Distributor
                </a>
                <a href="{{ route('customer.index') }}" class="sidebar-item {{ request()->routeIs('customer.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="users" style="width:18px;height:18px;"></i> Customer
                </a>
                <a href="{{ route('salesman.index') }}" class="sidebar-item {{ request()->routeIs('salesman.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="user-check" style="width:18px;height:18px;"></i> Salesman
                </a>
                <a href="{{ route('product.index') }}" class="sidebar-item {{ request()->routeIs('product.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="package" style="width:18px;height:18px;"></i> Produk
                </a>
                
                <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">Transaksi</div>
                <a href="{{ route('sale.index') }}" class="sidebar-item {{ request()->routeIs('sale.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="shopping-cart" style="width:18px;height:18px;"></i> Penjualan
                </a>
                <a href="{{ route('cash-flow.index') }}" class="sidebar-item {{ request()->routeIs('cash-flow.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="landmark" style="width:18px;height:18px;"></i> Kas / Bank
                </a>
                
                <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">Laporan</div>
                <a href="{{ route('report.closing') }}" class="sidebar-item {{ request()->routeIs('report.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="clipboard-check" style="width:18px;height:18px;"></i> Closing / Assessment
                </a>
            </nav>
            
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-bold">A</div>
                    <div class="text-xs">
                        <div class="font-semibold">Admin</div>
                        <div class="text-slate-400">admin@mail.com</div>
                    </div>
                </div>
            </div>
        </aside>
        
        {{-- Main Content --}}
        <main class="flex-1 flex flex-col h-full overflow-auto">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center justify-between flex-shrink-0">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400">@yield('page-subtitle', 'Ringkasan data penjualan')</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" style="width:16px;height:16px;"></i>
                        <input type="text" placeholder="Cari..." class="pl-9 pr-4 py-2 text-sm bg-slate-100 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-emerald-500 w-52">
                    </div>
                    <button class="relative p-2 rounded-lg hover:bg-slate-100">
                        <i data-lucide="bell" style="width:18px;height:18px;" class="text-slate-500"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </header>
            
            {{-- Content --}}
            <div class="flex-1 p-6 overflow-auto fade-in">
                @if(session('success'))
                    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
    
    @stack('scripts')
</body>
</html>