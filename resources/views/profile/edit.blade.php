@extends('layouts.admin')

@section('title', 'Profile')
@section('page-title', 'Profile')
@section('page-subtitle', 'Kelola akun & keamanan')

@section('content')
<div class="max-w-5xl space-y-4">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="max-w-2xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="max-w-2xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="max-w-2xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
