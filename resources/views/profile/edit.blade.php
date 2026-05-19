@extends('layouts.admin')

@section('title', 'Profile')
@section('page-title', 'Profile Saya')
@section('page-subtitle', 'Kelola informasi pribadi, keamanan akun, dan preferensi.')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Navigation Sidebar (Desktop) -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-2">
                <a href="#personal-info" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-sm transition-all border border-indigo-100/50 shadow-sm">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    Profil & Data Diri
                </a>
                <a href="#password-security" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-bold text-sm transition-all border border-transparent">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    Keamanan Password
                </a>
                <a href="#danger-zone" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 font-bold text-sm transition-all border border-transparent">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Hapus Akun
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Personal Information Section -->
            <div id="personal-info" class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="p-8 md:p-10">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update Section -->
            <div id="password-security" class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="p-8 md:p-10">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Account Deletion Section -->
            <div id="danger-zone" class="bg-red-50/30 rounded-2xl border border-red-100 overflow-hidden">
                <div class="p-8 md:p-10">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple smooth scroll and active state (optional improvement)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
            
            // Update active state
            document.querySelectorAll('a[href^="#"]').forEach(a => {
                a.classList.remove('bg-indigo-50', 'text-indigo-700', 'border-indigo-100/50', 'shadow-sm');
                a.classList.add('text-slate-600', 'border-transparent');
            });
            this.classList.add('bg-indigo-50', 'text-indigo-700', 'border-indigo-100/50', 'shadow-sm');
            this.classList.remove('text-slate-600', 'border-transparent');
        });
    });
</script>
@endsection
