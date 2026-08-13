<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- KARTU DASHBOARD UTAMA -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-emerald-500 p-6">
                <h2 class="text-xl font-bold text-blue-900">Dashboard MBG FC</h2>
                <p class="text-gray-600 mt-1">
                    Selamat Datang Kembali, <span class="text-emerald-600 font-semibold">{{ auth()->user()->name }}</span>!
                </p>
            </div>
        </div>
    </div>
</x-app-layout>