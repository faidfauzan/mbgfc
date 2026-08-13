<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- KARTU 1: UPDATE PROFILE INFORMATION -->
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-xl border-t-4 border-emerald-500">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- KARTU 2: UPDATE PASSWORD -->
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-xl border-t-4 border-emerald-500">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- KARTU 3: DELETE USER -->
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-xl border-t-4 border-rose-500">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>