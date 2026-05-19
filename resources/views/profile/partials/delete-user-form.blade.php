<section class="space-y-6">
    <header class="mb-6">
        <h2 class="text-xl font-black text-red-600 tracking-tight flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __('Setelah akun dihapus, semua data dan sumber daya terkait akan dihapus secara permanen. Pastikan Anda telah mencadangkan data penting.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700 text-white font-black py-2.5 px-6 rounded-xl text-sm transition-all shadow-lg shadow-red-100 hover:-translate-y-0.5 active:scale-95 flex items-center gap-2"
    >
        <i data-lucide="trash-2" class="w-4 h-4"></i>
        {{ __('Hapus Akun Permanen') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-slate-800 tracking-tight">
                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                {{ __('Tindakan ini tidak dapat dibatalkan. Harap masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun ini secara permanen.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                    </div>
                    <input id="password" name="password" type="password" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        placeholder="Masukkan password konfirmasi...">
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:text-slate-800 bg-white border border-slate-200 hover:border-slate-300 transition-all">
                    {{ __('Batal') }}
                </button>

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-black py-2.5 px-6 rounded-xl text-sm transition-all shadow-lg shadow-red-100">
                    {{ __('Ya, Hapus Sekarang') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
