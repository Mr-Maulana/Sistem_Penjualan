<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" type="image/png" href="{{ \App\Helpers\SettingsHelper::logoUrl() }}">
        <title>{{ \App\Helpers\SettingsHelper::get('app_name', config('app.name', 'SIMPEL')) }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        @php($theme = \App\Helpers\SettingsHelper::getThemeDetails())
        <style>
            :root {
                --theme-gradient-from: {{ $theme['gradient_from'] }};
                --theme-gradient-to: {{ $theme['gradient_to'] }};
            }
            /* Override gradient blue to indigo buttons */
            .from-blue-600.to-indigo-700 {
                background-image: linear-gradient(to right, var(--theme-gradient-from), var(--theme-gradient-to)) !important;
            }
            .text-blue-400 {
                color: {{ $theme['primary'] }} !important;
            }
        </style>
    </head>
    <body class="h-full font-sans antialiased">
        <div class="min-h-screen relative overflow-hidden flex items-center justify-center p-6 bg-slate-900">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ \App\Helpers\SettingsHelper::loginBgUrl() }}" alt="Background" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/40"></div>
            </div>

            <div class="absolute -top-24 -left-24 w-96 h-92 rounded-full bg-blue-500/10 blur-[120px] z-0"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-indigo-500/10 blur-[120px] z-0"></div>

            <div class="relative w-full max-w-md">
                <div class="mb-8 text-center">
                    <div class="inline-flex flex-col items-center gap-6">

                        <div class="text-center">
                            <h1 class="text-5xl md:text-6xl font-black tracking-tighter mb-1 relative inline-block">
                                <span class="text-transparent bg-clip-text bg-gradient-to-br from-white via-white to-slate-400">SIM</span><span class="text-transparent bg-clip-text bg-gradient-to-br from-blue-400 to-indigo-600" style="filter: drop-shadow(0 0 15px rgba(59,130,246,0.4));">PEL</span>
                            </h1>
                            <p class="text-slate-400/90 text-[10px] md:text-xs font-black uppercase tracking-[0.25em] mt-2 leading-relaxed">
                                Sistem Informasi Manajemen<br/>Penjualan Lengkap
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 backdrop-blur rounded-2xl shadow-xl border border-white/15 overflow-hidden">
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </div>

                <div class="mt-4 text-center text-xs text-slate-400">
                    © {{ now()->year }} SIMPEL (Sistem Info Manajemen Penjualan Lengkap)
                </div>
            </div>
        </div>
        <script>lucide.createIcons();</script>
    </body>
</html>
