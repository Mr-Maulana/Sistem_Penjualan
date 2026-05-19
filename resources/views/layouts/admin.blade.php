{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ \App\Helpers\SettingsHelper::logoUrl() }}">
    <title>{{ \App\Helpers\SettingsHelper::get('app_name', config('app.name', 'Sistem Penjualan')) }} - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @php($theme = \App\Helpers\SettingsHelper::getThemeDetails())
    <style>
        [x-cloak] { display: none !important; }
        
        :root {
            --theme-primary: {{ $theme['primary'] }};
            --theme-hover: {{ $theme['hover'] }};
            --theme-light: {{ $theme['light'] }};
            --theme-gradient-from: {{ $theme['gradient_from'] }};
            --theme-gradient-to: {{ $theme['gradient_to'] }};
        }

        /* Override bg-indigo-600 */
        .bg-indigo-600 {
            background-color: var(--theme-primary) !important;
        }
        /* Override text-indigo-600 and text-indigo-700 */
        .text-indigo-600, .text-indigo-700 {
            color: var(--theme-primary) !important;
        }
        /* Override hover backgrounds */
        .hover\:bg-indigo-50:hover, .hover\:bg-emerald-50:hover {
            background-color: var(--theme-light) !important;
        }
        /* Override active sidebar item */
        .sidebar-item.active {
            background-color: var(--theme-primary) !important;
            color: white !important;
        }
        /* Override emerald text/colors where used for indicators */
        .text-emerald-600 {
            color: var(--theme-primary) !important;
        }
        .bg-emerald-50 {
            background-color: var(--theme-light) !important;
        }
        .border-emerald-100 {
            border-color: var(--theme-light) !important;
        }
        /* Gradient buttons / backgrounds */
        .bg-gradient-to-r.from-indigo-600.to-indigo-700,
        .bg-gradient-to-r.from-emerald-500.to-teal-500,
        .bg-gradient-to-r.from-blue-600.to-indigo-700 {
            background-image: linear-gradient(to right, var(--theme-gradient-from), var(--theme-gradient-to)) !important;
        }
        /* Theme button text */
        .hover\:text-emerald-600:hover {
            color: var(--theme-primary) !important;
        }
        .hover\:bg-emerald-50:hover {
            background-color: var(--theme-light) !important;
        }
        .bg-emerald-600 {
            background-color: var(--theme-primary) !important;
        }
        .bg-indigo-50 {
            background-color: var(--theme-light) !important;
        }
        .text-indigo-700 {
            color: var(--theme-primary) !important;
        }

        /* Dark Mode Theme Overrides */
        @if(\App\Helpers\SettingsHelper::get('dark_mode', false))
        body, .bg-slate-100 {
            background-color: #0b0f19 !important;
            color: #f1f5f9 !important;
        }
        main, .bg-slate-50 {
            background-color: #0b0f19 !important;
        }
        /* Cards */
        .bg-white {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }
        /* Headers and subheadings */
        .text-slate-800, .text-slate-900, .text-slate-700 {
            color: #f8fafc !important;
        }
        .text-slate-500, .text-slate-600, .text-slate-400 {
            color: #94a3b8 !important;
        }
        .bg-slate-50, .bg-slate-50\/50, .bg-slate-50\/30 {
            background-color: #1f2937 !important;
        }
        /* Borders */
        .border-slate-200\/60, .border-slate-100, .border-slate-200, .border-slate-100\/80, .border-slate-200\/80, .border-b, .border-t, .border-l, .border-r {
            border-color: #1f2937 !important;
        }
        /* Input fields and selects */
        input[type="text"], input[type="email"], input[type="number"], input[type="password"], input[type="search"], select, textarea {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #f3f4f6 !important;
        }
        /* Tables */
        table th {
            background-color: #1f2937 !important;
            color: #94a3b8 !important;
            border-color: #374151 !important;
        }
        table td {
            color: #e5e7eb !important;
            border-color: #1f2937 !important;
        }
        /* Header border */
        header {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }
        /* Profile dropdown button */
        .hover\:bg-slate-50:hover {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }
        /* Active states / lists */
        .bg-slate-100 {
            background-color: #1f2937 !important;
        }
        /* Modals and overlay dialog boxes */
        [x-show="modalOpen"] .bg-white, .modal-content {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }
        @endif
    </style>
</head>
<body class="h-full bg-slate-100" x-data="{ sidebarOpen: false }">
    <div class="h-full w-full flex overflow-hidden relative">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-white flex flex-col flex-shrink-0 h-full z-50 transition-transform duration-300 lg:static lg:translate-x-0">
            <div class="p-5 border-b border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ \App\Helpers\SettingsHelper::logoUrl() }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover">
                    <div>
                        <div class="font-bold text-sm leading-tight text-white">Sistem Penjualan</div>
                        <div class="text-xs text-slate-400">PT. Maju Bersama</div>
                    </div>
                </div>
                <!-- Close Button (Mobile Only) -->
                <button @click="sidebarOpen = false" class="lg:hidden p-1 hover:bg-white/10 rounded-lg text-slate-400">
                    <i data-lucide="x" style="width:20px;height:20px;"></i>
                </button>
            </div>

            <nav class="flex-1 py-4 overflow-auto">
                <div class="px-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">Dashboard</div>
                <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="layout-dashboard" style="width:18px;height:18px;"></i> Dashboard
                </a>

                <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">Master Data</div>
                @if(!in_array(auth()->user()?->role, ['sales', 'supervisor']))
                <a href="{{ route('supplier.index') }}" class="sidebar-item {{ request()->routeIs('supplier.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="truck" style="width:18px;height:18px;"></i> Supplier
                </a>
                @endif
                <a href="{{ route('customer.index') }}" class="sidebar-item {{ request()->routeIs('customer.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="users" style="width:18px;height:18px;"></i> Customer
                </a>
                @if(auth()->user()?->role !== 'sales')
                <a href="{{ route('salesman.index') }}" class="sidebar-item {{ request()->routeIs('salesman.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="user-check" style="width:18px;height:18px;"></i> Salesman
                </a>
                @endif
                @if(!in_array(auth()->user()?->role, ['sales', 'supervisor']))
                <a href="{{ route('product.index') }}" class="sidebar-item {{ request()->routeIs('product.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="package" style="width:18px;height:18px;"></i> Produk
                </a>
                @endif
                <a href="{{ route('price.index') }}" class="sidebar-item {{ request()->routeIs('price.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="tag" style="width:18px;height:18px;"></i> Harga
                </a>
                @if(!in_array(auth()->user()?->role, ['sales', 'supervisor', 'manager']))
                <a href="{{ route('area.index') }}" class="sidebar-item {{ request()->routeIs('area.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="map" style="width:18px;height:18px;"></i> Wilayah / Area
                </a>
                @endif
                
                <a href="{{ route('team.index') }}" class="sidebar-item {{ request()->routeIs('team.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="network" style="width:18px;height:18px;"></i> Struktur Tim
                </a>

                <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">Transaksi</div>
                <a href="{{ route('sale.index') }}" class="sidebar-item {{ request()->routeIs('sale.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="shopping-cart" style="width:18px;height:18px;"></i> Penjualan
                </a>
                @if(in_array(auth()->user()?->role, ['admin', 'manager']))
                <a href="{{ route('cash-flow.index') }}" class="sidebar-item {{ request()->routeIs('cash-flow.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="landmark" style="width:18px;height:18px;"></i> Kas / Bank
                </a>
                @endif

                <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">Laporan</div>
                <a href="{{ route('report.closing') }}" class="sidebar-item {{ request()->routeIs('report.closing') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="clipboard-check" style="width:18px;height:18px;"></i> Closing / Assessment
                </a>
                <a href="{{ route('report.sales') }}" class="sidebar-item {{ request()->routeIs('report.sales*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="bar-chart-3" style="width:18px;height:18px;"></i> Laporan Penjualan
                </a>
                @if(in_array(auth()->user()?->role, ['admin', 'manager']))
                <a href="{{ route('report.cash-flow') }}" class="sidebar-item {{ request()->routeIs('report.cash-flow*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="wallet" style="width:18px;height:18px;"></i> Laporan Kas / Bank
                </a>
                @endif

                @if(auth()->user()?->role === 'admin')
                    <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">System Administration</div>
                    <a href="{{ route('user.index') }}" class="sidebar-item {{ request()->routeIs('user.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                        <i data-lucide="shield" style="width:18px;height:18px;"></i> User & Role
                    </a>
                    <a href="{{ route('admin.settings') }}" class="sidebar-item {{ request()->routeIs('admin.settings') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                        <i data-lucide="settings" style="width:18px;height:18px;"></i> Pengaturan Sistem
                    </a>

                    <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">System Overview</div>
                    <a href="{{ route('admin.records') }}" class="sidebar-item {{ request()->routeIs('admin.records') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                        <i data-lucide="database" style="width:18px;height:18px;"></i> Data Records
                    </a>
                    <a href="{{ route('admin.activity') }}" class="sidebar-item {{ request()->routeIs('admin.activity') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                        <i data-lucide="activity" style="width:18px;height:18px;"></i> Activity
                    </a>
                    <a href="{{ route('admin.health') }}" class="sidebar-item {{ request()->routeIs('admin.health') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                        <i data-lucide="heart-pulse" style="width:18px;height:18px;"></i> Health Status
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    @php($u = auth()->user())
                    @php($photo = $u?->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($u->profile_photo_path) : null)
                    @if($photo)
                        <img src="{{ $photo }}" alt="Profile photo" class="w-8 h-8 rounded-full object-cover ring-2 ring-white/10">
                    @else
                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(mb_substr($u?->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-xs flex-1 min-w-0">
                        <div class="font-semibold truncate">{{ $u?->name ?? 'User' }}</div>
                        <div class="text-slate-400 truncate">{{ $u?->email ?? '' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg hover:bg-white/10 text-slate-200" title="Logout">
                            <i data-lucide="log-out" style="width:18px;height:18px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay (Mobile Only) -->
        <div x-show="sidebarOpen" x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"></div>

        <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50">
            <header class="bg-white border-b border-slate-200 px-4 lg:px-8 py-4 flex items-center justify-between flex-shrink-0 z-30">
                <div class="flex items-center gap-4">
                    <!-- Burger Menu (Mobile Only) -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        <i data-lucide="menu" style="width:24px;height:24px;"></i>
                    </button>
                    <div>
                        <h1 class="text-lg lg:text-xl font-extrabold text-slate-800 tracking-tight">@yield('page-title', 'Dashboard')</h1>
                        <p class="hidden sm:block text-sm text-slate-500 mt-0.5">@yield('page-subtitle', 'Ringkasan data penjualan')</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Quick Dark Mode Toggle -->
                    <form action="{{ route('admin.settings.toggle-dark') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 text-slate-600 hover:text-slate-950 flex items-center justify-center transition-all hover:bg-slate-100 shadow-sm" style="background-color: var(--bg-white, inherit);" title="Toggle Dark Mode">
                            @if(\App\Helpers\SettingsHelper::get('dark_mode', false))
                                <i data-lucide="sun" class="w-5 h-5 text-amber-500"></i>
                            @else
                                <i data-lucide="moon" class="w-5 h-5"></i>
                            @endif
                        </button>
                    </form>

                    @php($uTop = auth()->user())
                    @php($topPhoto = $uTop?->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($uTop->profile_photo_path) : null)
                    <div x-data="{ open:false }" class="relative">
                        <button type="button" @click="open = !open" @keydown.escape.window="open=false"
                            class="flex items-center gap-3 hover:bg-slate-50 text-slate-700 px-2 py-1.5 rounded-2xl transition-all border border-transparent hover:border-slate-200">
                            @if($topPhoto)
                                <img src="{{ $topPhoto }}" alt="Profile photo" class="w-10 h-10 rounded-full object-cover shadow-sm ring-2 ring-white">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-sm font-bold text-white shadow-sm ring-2 ring-white">
                                    {{ strtoupper(mb_substr($uTop?->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="text-left hidden md:block pr-2">
                                <div class="text-sm font-bold leading-none text-slate-800">{{ $uTop?->name ?? 'User' }}</div>
                                <div class="text-xs text-emerald-600 font-medium mt-1 uppercase tracking-wider">{{ $uTop?->role ?? '' }}</div>
                            </div>
                            <i data-lucide="chevron-down" style="width:16px;height:16px;" class="text-slate-400"></i>
                        </button>

                        <div x-show="open" x-cloak x-transition.origin.top.right @click.outside="open=false"
                            class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden z-50">
                            <div class="p-4 border-b border-slate-50">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $uTop?->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $uTop?->email }}</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-colors">
                                    <i data-lucide="user" style="width:16px;height:16px;"></i> My Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition-colors mt-1">
                                        <i data-lucide="log-out" style="width:16px;height:16px;"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-4 lg:p-6 overflow-auto relative z-0 animate-slide-up">
                @if(session('success'))
                    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>

    @stack('modals')
    @stack('scripts')
</body>
</html>

