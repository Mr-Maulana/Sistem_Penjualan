<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-[#f8fafc] overflow-hidden">
        <div x-data="{ sidebarOpen: false }" class="flex h-full">
            
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                
                <!-- Topbar -->
                <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 px-8 hidden lg:flex items-center justify-between z-10">
                    <div>
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>

                    <div class="flex items-center gap-6">
                        <!-- Notifications -->
                        <button class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all relative">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="h-10 w-px bg-slate-100 mx-2"></div>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-3 group">
                                    <div class="text-right hidden sm:block">
                                        <p class="text-sm font-black text-slate-800 group-hover:text-indigo-600 transition-colors">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ Auth::user()->profesi ?? 'User' }}</p>
                                    </div>
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden group-hover:border-indigo-200 transition-all">
                                        @php($photo = Auth::user()->profile_photo_path ? \Illuminate\Support\Facades\Storage::url(Auth::user()->profile_photo_path) : null)
                                        @if($photo)
                                            <img src="{{ $photo }}" class="w-full h-full object-cover" alt="">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-bold text-indigo-600">
                                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                                    <i data-lucide="user-cog" class="w-4 h-4"></i>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="flex items-center gap-2 text-rose-600 hover:text-rose-700">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 lg:p-8 pt-20 lg:pt-8 scrollbar-hide">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Lucide Icons Script -->
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();
            // Re-initialize icons on dynamic content changes if needed
            document.addEventListener('alpine:initialized', () => {
                lucide.createIcons();
            });
        </script>
    </body>
</html>
