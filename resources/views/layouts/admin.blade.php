{{-- resources/views/layouts/admin.blade.php --}}
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
                <a href="{{ route('price.index') }}" class="sidebar-item {{ request()->routeIs('price.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="tag" style="width:18px;height:18px;"></i> Harga
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
                <a href="{{ route('report.sales') }}" class="sidebar-item {{ request()->routeIs('report.sales') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="bar-chart-3" style="width:18px;height:18px;"></i> Laporan Penjualan
                </a>
                <a href="{{ route('report.cash-flow') }}" class="sidebar-item {{ request()->routeIs('report.cash-flow') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                    <i data-lucide="wallet" style="width:18px;height:18px;"></i> Laporan Kas / Bank
                </a>

                @if(auth()->user()?->role === 'admin')
                    <div class="px-4 mt-4 mb-2 text-[10px] tracking-widest uppercase text-slate-500 font-semibold">System</div>
                    <a href="{{ route('user.index') }}" class="sidebar-item {{ request()->routeIs('user.*') ? 'active' : '' }} w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm rounded-lg mx-2 mb-0.5" style="width:calc(100% - 16px)">
                        <i data-lucide="shield" style="width:18px;height:18px;"></i> User & Role
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    @php($u = auth()->user())
                    @php($photo = $u?->profile_photo_path ? asset('storage/'.$u->profile_photo_path) : null)
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

        <main class="flex-1 flex flex-col h-full overflow-auto">
            <header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center justify-between flex-shrink-0">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400">@yield('page-subtitle', 'Ringkasan data penjualan')</p>
                </div>
                <div class="flex items-center gap-3">
                    @php($uTop = auth()->user())
                    @php($topPhoto = $uTop?->profile_photo_path ? asset('storage/'.$uTop->profile_photo_path) : null)
                    <div x-data="{ open:false }" class="relative">
                        <button type="button" @click="open = !open" @keydown.escape.window="open=false"
                            class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-1.5 rounded-xl transition">
                            @if($topPhoto)
                                <img src="{{ $topPhoto }}" alt="Profile photo" class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-bold text-white">
                                    {{ strtoupper(mb_substr($uTop?->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="text-left hidden md:block">
                                <div class="text-xs font-bold leading-tight truncate max-w-[140px]">{{ $uTop?->name ?? 'User' }}</div>
                                <div class="text-[10px] text-slate-500 leading-tight truncate max-w-[140px]">{{ strtoupper($uTop?->role ?? '') }}</div>
                            </div>
                            <i data-lucide="chevron-down" style="width:16px;height:16px;" class="text-slate-500"></i>
                        </button>

                        <div x-show="open" x-transition.origin.top.right @click.outside="open=false"
                            class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden z-50">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">
                                <i data-lucide="user" style="width:16px;height:16px;"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                                    <i data-lucide="log-out" style="width:16px;height:16px;"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

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
        lucide.createIcons();
    </script>

    @stack('scripts')
</body>
</html>

