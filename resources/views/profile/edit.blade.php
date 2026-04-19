@extends('layouts.main')

@section('title', 'Pengaturan Profil - Becks Apparel')

@section('content')
    <div class="min-h-screen bg-[#fdfbf7] py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
            <!-- Header -->
            <div class="mb-12 md:mb-20">
                <h1 class="text-4xl md:text-7xl font-black text-brand-900 tracking-tighter uppercase mb-4">
                    Pengaturan <span class="text-brand-600">Profil</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl">
                    Kelola informasi akun, keamanan, dan preferensi profil Anda.
                </p>
            </div>

            <!-- Content Area -->
            <div class="space-y-8 animate-scale-up">
                <!-- Update Profile Info -->
                <div class="p-6 md:p-10 bg-white rounded-[2rem] shadow-xl border border-gray-100">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="p-6 md:p-10 bg-white rounded-[2rem] shadow-xl border border-gray-100">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="p-6 md:p-10 bg-white rounded-[2rem] shadow-xl border border-gray-100 border-red-100">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
