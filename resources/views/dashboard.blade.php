<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- 1. TAMPILAN KHUSUS ADMIN / CAPTAIN --}}
            @if(auth()->user()->role === 'captain' || auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')

                <!-- Banner Admin -->
                <div class="bg-white rounded-xl shadow-sm border-t-4 border-emerald-500 p-6">
                    <h2 class="text-xl font-bold text-blue-900">Dashboard Admin MBG FC</h2>
                    <p class="text-gray-600 mt-1">
                        Selamat Datang Kembali, <span
                            class="text-emerald-600 font-semibold">{{ auth()->user()->name }}</span>!
                    </p>
                </div>

                <!-- Card Statistik Admin -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- CARD 1: STATISTIK MEMBER -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between hover:shadow-md transition">
                        <div>
                            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Member</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">
                                {{ $totalMembers ?? 0 }} <span class="text-base font-normal text-slate-500">Member</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-2">Jumlah member terdaftar.</p>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- CARD 2: STATISTIK MATCHDAY -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between hover:shadow-md transition">
                        <div>
                            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Matchday</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">
                                {{ $totalMatchdays ?? 0 }} <span
                                    class="text-base font-normal text-slate-500">Matchday</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-2">Jadwal yang telah dibuat.</p>
                        </div>
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- CARD 3: MEMBER PRIORITAS + FORM KUOTA -->
                    <div
                        class="bg-amber-50 rounded-xl shadow-sm border border-amber-200 p-6 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-amber-700 uppercase tracking-wider">Member Prioritas</p>
                                <span
                                    class="text-xs bg-amber-200 text-amber-800 font-bold px-2 py-0.5 rounded-full">Slot</span>
                            </div>
                            <h3 class="text-3xl font-extrabold text-slate-900 mt-2">
                                {{ $totalPrioritas ?? 0 }} <span class="text-base font-normal text-amber-800">/
                                    {{ $maxQuota ?? 15 }}</span>
                            </h3>
                        </div>

                        <!-- Form Setting Kuota -->
                        <form action="{{ route('admin.prioritas.updateQuota') }}" method="POST" class="mt-4 flex gap-2">
                            @csrf
                            <input type="number" name="max_quota" value="{{ $maxQuota ?? 15 }}" min="0" required
                                class="w-full text-xs rounded-lg border-amber-300 bg-white text-slate-900 font-bold focus:ring-amber-500 focus:border-amber-500 py-1.5 px-2">
                            <button type="submit"
                                class="bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs py-1.5 px-3 rounded-lg transition whitespace-nowrap">
                                Set Kuota
                            </button>
                        </form>
                    </div>

                    <!-- CARD 4: MEMBER REGULER -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between hover:shadow-md transition">
                        <div>
                            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Member Reguler</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">
                                {{ $totalReguler ?? 0 }} <span class="text-base font-normal text-slate-500">Member</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-2">Member berstatus umum.</p>
                        </div>
                        <div class="p-3 bg-slate-100 text-slate-600 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>

                </div>

                <!-- batas role -->

                {{-- 2. TAMPILAN KHUSUS MEMBER BIASA --}}
            @else

                <!-- Banner Member & Status -->
                <div
                    class="bg-white rounded-xl shadow-sm border-t-4 border-blue-500 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-blue-900">Halo, {{ auth()->user()->name }}! 👋</h2>
                        <p class="text-gray-600 mt-1">Selamat datang di Portal Member MBG FC.</p>
                    </div>
                    <div>
                        @if($member && $member->isPrioritasActive())
                            <span
                                class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-800 font-semibold px-4 py-2 rounded-xl text-sm border border-amber-300 shadow-sm">
                                kamu telah menjadi Member Prioritas (s.d {{ optional($member->tanggal_berakhir_prioritas)->format('d M Y') }})
                            </span>
                        @else
                            <span
                                class="inline-flex items-center bg-slate-100 text-slate-700 font-medium px-4 py-2 rounded-xl text-sm border border-slate-200">
                                Member Reguler
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Banner Promo Upgrade Prioritas (Bila Masih Reguler) -->
                @if(!$member || !$member->isPrioritasActive())
                    <div
                        class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl shadow-md border border-amber-500/30 p-6 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-amber-400 flex items-center gap-2">
                                🔥 Upgrade ke Member Prioritas!
                            </h3>
                            <p class="text-slate-300 text-sm mt-1">
                                Dapatkan keutamaan slot bermain matchday dan amankan posisimu dari slot umum!
                            </p>
                        </div>
                        <div>
                            @if($isQuotaFull)
                                <button disabled
                                    class="bg-slate-700 text-slate-400 font-semibold text-sm px-4 py-2.5 rounded-xl cursor-not-allowed">
                                    Kuota Full ({{ $activePrioritasCount }}/{{ $maxQuota }})
                                </button>
                            @else
                                <a href="{{ route('prioritas.create') }}"
                                    class="inline-block bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-sm px-5 py-2.5 rounded-xl shadow transition">
                                    Daftar Prioritas Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Tampilan Utama Member -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-slate-600">Jadwal Matchday mendatang dan status pendaftaran kamu akan tampil di sini.</p>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>