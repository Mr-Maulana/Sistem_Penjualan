<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            @error('email') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            @error('password') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" name="remember">
                Remember me
            </label>
        </div>

        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
            Masuk
        </button>
    </form>
</x-guest-layout>
