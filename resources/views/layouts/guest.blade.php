<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
                    <div class="inline-flex flex-col items-center gap-4">
                        <div class="w-16 h-16 rounded-[2rem] bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center font-black text-white text-3xl shadow-xl shadow-indigo-500/20 rotate-3 hover:rotate-0 transition-transform duration-500">S</div>
                        <div>
                            <h1 class="text-white text-2xl font-black tracking-tight">Sistem Penjualan</h1>
                            <p class="text-slate-400 text-sm font-medium mt-1">Management Dashboard v2.0</p>
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
