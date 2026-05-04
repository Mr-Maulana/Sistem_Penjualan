<section>
    <header class="mb-8">
        <h2 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
            <i data-lucide="user-circle" class="w-6 h-6 text-indigo-600"></i>
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __("Perbarui informasi profil akun dan data diri Anda di bawah ini.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-8" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo Section -->
        <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
            <x-input-label for="profile_photo" :value="__('Foto Profil')" class="text-xs font-black uppercase tracking-widest text-slate-500 mb-4" />
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative group">
                    @php($photo = $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) : null)
                    @if($photo)
                        <img src="{{ $photo }}" alt="Profile photo" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white shadow-xl group-hover:ring-indigo-100 transition-all">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-3xl ring-4 ring-white shadow-xl group-hover:bg-indigo-700 transition-all">
                            {{ strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="absolute -bottom-2 -right-2 bg-white p-1.5 rounded-lg shadow-md border border-slate-100">
                        <i data-lucide="camera" class="w-4 h-4 text-indigo-600"></i>
                    </div>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <input id="profile_photo" name="profile_photo" type="file" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer file:transition-all">
                    <p class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Format JPG, PNG atau WebP (Maks. 2MB)</p>
                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                </div>
            </div>
        </div>

        <!-- Account Data -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="name" name="name" type="text" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="email" name="email" type="email" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        value="{{ old('email', $user->email) }}" required autocomplete="username">
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 bg-amber-50 p-3 rounded-lg border border-amber-100 flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600"></i>
                        <p class="text-xs text-amber-800 font-medium">
                            {{ __('Email Anda belum terverifikasi.') }}
                            <button form="send-verification" class="ml-1 underline text-amber-700 hover:text-amber-900 font-bold">
                                {{ __('Kirim ulang verifikasi.') }}
                            </button>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="h-px bg-slate-100 my-4"></div>

        <!-- Personal Data -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <x-input-label for="nik" :value="__('NIK (KTP)')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="credit-card" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="nik" name="nik" type="text" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        value="{{ old('nik', $user->nik) }}" placeholder="16 Digit NIK">
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('nik')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="nip" :value="__('NIP (Pegawai)')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="badge-id" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="nip" name="nip" type="text" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai">
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('nip')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="profesi" :value="__('Profesi / Jabatan')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="briefcase" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="profesi" name="profesi" type="text" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        value="{{ old('profesi', $user->profesi) }}" placeholder="Contoh: Senior Manager">
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('profesi')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="phone" :value="__('Nomor HP')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="phone" name="phone" type="text" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        value="{{ old('phone', $user->phone) }}" placeholder="0812xxxx">
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('phone')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="gender" :value="__('Jenis Kelamin')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="flex items-center gap-6 mt-2 h-[42px] px-3 bg-slate-50/50 rounded-xl border border-slate-100">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="gender" value="L" {{ old('gender', $user->gender) === 'L' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                        <span class="text-sm text-slate-600 font-medium group-hover:text-slate-900 transition-colors">Laki-laki</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="gender" value="P" {{ old('gender', $user->gender) === 'P' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                        <span class="text-sm text-slate-600 font-medium group-hover:text-slate-900 transition-colors">Perempuan</span>
                    </label>
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('gender')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="birth_date" :value="__('Tanggal Lahir')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input id="birth_date" name="birth_date" type="date" 
                        class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                        value="{{ old('birth_date', $user->birth_date) }}">
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('birth_date')" />
            </div>

            <div class="md:col-span-2 space-y-2">
                <x-input-label for="address" :value="__('Alamat Tinggal')" class="text-xs font-bold uppercase tracking-wide ml-1" />
                <textarea id="address" name="address" rows="3" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all bg-white hover:bg-slate-50/50 shadow-sm"
                    placeholder="Alamat lengkap sesuai KTP...">{{ old('address', $user->address) }}</textarea>
                <x-input-error class="mt-1" :messages="$errors->get('address')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2.5 px-8 rounded-xl text-sm transition-all shadow-lg shadow-indigo-100 hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-emerald-600 font-bold text-sm bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100"
                >
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    {{ __('Data Berhasil Disimpan.') }}
                </div>
            @endif
        </div>
    </form>
</section>
