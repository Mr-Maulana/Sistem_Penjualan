<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <title>{{ config('app.name', 'Sistem Penjualan') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    </head>
    <body class="h-full font-sans antialiased">
        <div class="min-h-screen relative overflow-hidden flex items-center justify-center p-6 bg-slate-900">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/login-bg.png') }}" alt="Background" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/40"></div>
            </div>

            <div class="absolute -top-24 -left-24 w-96 h-92 rounded-full bg-blue-500/10 blur-[120px] z-0"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-indigo-500/10 blur-[120px] z-0"></div>

            <div class="relative w-full max-w-md">
                <div class="mb-8 text-center">
                    <div class="inline-flex flex-col items-center gap-6">
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                            <div class="relative p-2 bg-white/10 backdrop-blur-2xl rounded-full border border-white/20 shadow-2xl">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24 rounded-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                        </div>
                        <div>
                            <h1 class="text-white text-3xl font-black tracking-tight">Sistem<span class="text-blue-400">Penjualan</span></h1>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-2">Management Dashboard v2.0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 backdrop-blur rounded-2xl shadow-xl border border-white/15 overflow-hidden">
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </div>

                <div class="mt-4 text-center text-xs text-slate-400">
                    © {{ now()->year }} Sistem Penjualan
                </div>
            </div>
        </div>
        <script>lucide.createIcons();</script>
    </body>
</html>
