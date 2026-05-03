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
    <body class="h-full font-sans antialiased bg-slate-950">
        <div class="min-h-screen relative overflow-hidden flex items-center justify-center p-6">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950"></div>
            <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-emerald-500/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-72 h-72 rounded-full bg-sky-500/15 blur-3xl"></div>

            <div class="relative w-full max-w-md">
                <div class="mb-4 text-center">
                    <div class="inline-flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-500 flex items-center justify-center font-extrabold text-white text-lg">S</div>
                        <div class="text-left">
                            <div class="text-white font-extrabold leading-tight">Sistem Penjualan</div>
                            <div class="text-xs text-slate-300">Masuk untuk melanjutkan</div>
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
