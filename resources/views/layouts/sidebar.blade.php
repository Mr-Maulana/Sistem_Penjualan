<div x-data="{ sidebarOpen: false }" class="relative">
    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"></div>

    <!-- Sidebar Container -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-100 z-50 transform transition-transform duration-300 ease-in-out lg:static lg:inset-auto">
        
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center px-8 border-b border-slate-50">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
                <span class="font-black text-xl tracking-tight text-slate-800">Sistem<span class="text-indigo-600">JP</span></span>
            </a>
        </div>

        <!-- Sidebar Content -->
        <div class="p-6 space-y-8 overflow-y-auto h-[calc(100vh-5rem)] scrollbar-hide">
            <!-- Menu Group -->
            <div>
                <h3 class="px-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Utama</h3>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-500 hover:bg-slate-50 hover:text-slate-800">
                        <i data-lucide="package" class="w-5 h-5"></i>
                        <span>Produk</span>
                    </a>
                </nav>
            </div>

            <!-- Management Group -->
            <div>
                <h3 class="px-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Manajemen</h3>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-500 hover:bg-slate-50 hover:text-slate-800">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Pelanggan</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-500 hover:bg-slate-50 hover:text-slate-800">
                        <i data-lucide="truck" class="w-5 h-5"></i>
                        <span>Distributor</span>
                    </a>
                </nav>
            </div>

            <!-- Profile Summary Card (Bottom) -->
            <div class="pt-8 mt-auto">
                <div class="bg-slate-900 rounded-2xl p-4 text-white relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-500/20 rounded-full blur-2xl group-hover:bg-indigo-500/40 transition-all"></div>
                    <div class="relative flex items-center gap-3">
                        @php($photo = Auth::user()->profile_photo_path ? \Illuminate\Support\Facades\Storage::url(Auth::user()->profile_photo_path) : null)
                        @if($photo)
                            <img src="{{ $photo }}" class="w-10 h-10 rounded-lg object-cover border border-white/20" alt="">
                        @else
                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Header/Toggle -->
    <div class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 flex items-center justify-between z-40">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i data-lucide="shopping-cart" class="w-5 h-5 text-white"></i>
            </div>
            <span class="font-black text-lg text-slate-800">Sistem<span class="text-indigo-600">JP</span></span>
        </div>
        <button @click="sidebarOpen = true" class="p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </div>
</div>
