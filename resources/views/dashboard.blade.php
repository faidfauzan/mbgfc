<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- KARTU SAMBUTAN UTAMA -->
            <div class="bg-white rounded-xl shadow-sm border-t-4 border-emerald-500 p-6">
                <h2 class="text-xl font-bold text-blue-900">Dashboard MBG FC</h2>
                <p class="text-gray-600 mt-1">
                    Selamat Datang Kembali, <span class="text-emerald-600 font-semibold">{{ auth()->user()->name }}</span>!
                </p>
            </div>

            <!-- GRID 2 KOLOM KARTU STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- CARD 1: STATISTIK MEMBER -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Member</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-2">
                            {{ $totalMembers ?? 0 }} <span class="text-base font-normal text-slate-500">Member</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-2">
                            Kamu saat ini memiliki <span class="font-semibold text-emerald-600">{{ $totalMembers ?? 0 }} member</span> yang telah bergabung.
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <!-- Icon Users -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- CARD 2: STATISTIK MATCHDAY -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Matchday</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-2">
                            {{ $totalMatchdays ?? 0 }} <span class="text-base font-normal text-slate-500">Matchday</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-2">
                            Kamu telah membuat <span class="font-semibold text-indigo-600">{{ $totalMatchdays ?? 0 }} jadwal matchday</span> sampai saat ini.
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <!-- Icon Calendar -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>