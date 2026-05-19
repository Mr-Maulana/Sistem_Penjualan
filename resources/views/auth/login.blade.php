<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="mail" style="width:18px;height:18px;" class="text-slate-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50 font-medium"
                    placeholder="Masukkan Email Anda">
            </div>
            @error('email') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
        </div>

        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-tighter">Forgot?</a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="lock" style="width:18px;height:18px;" class="text-slate-400"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50 font-medium"
                    placeholder="••••••••">
            </div>
            @error('password') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
        </div>

        <div class="flex items-center justify-between pt-1">
            <label class="inline-flex items-center gap-2 cursor-pointer group">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" class="peer h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/50 transition-all" name="remember">
                </div>
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider group-hover:text-slate-800 transition-colors">Ingat Saya</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white text-sm font-black px-4 py-4 rounded-xl shadow-lg shadow-indigo-500/25 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
            Masuk <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </form>
</x-guest-layout>
