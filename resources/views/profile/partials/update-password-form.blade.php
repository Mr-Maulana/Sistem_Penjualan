<section>
    <header class="mb-8">
        <h2 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
            <i data-lucide="shield-check" class="w-6 h-6 text-indigo-600"></i>
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __('Gunakan password yang kuat dan unik untuk menjaga keamanan akun Anda.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="text-xs font-bold uppercase tracking-wide ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="key-round" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input id="update_password_current_password" name="current_password" type="password" 
                    class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                    autocomplete="current-password">
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <x-input-label for="update_password_password" :value="__('Password Baru')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="update_password_password" name="password" type="password" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        autocomplete="new-password">
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
            </div>

            <div class="space-y-2">
                <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="check-circle" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        autocomplete="new-password">
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2.5 px-8 rounded-xl text-sm transition-all shadow-lg shadow-indigo-100 hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-emerald-600 font-bold text-sm bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100"
                >
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    {{ __('Password Berhasil Diperbarui.') }}
                </div>
            @endif
        </div>
    </form>
</section>
