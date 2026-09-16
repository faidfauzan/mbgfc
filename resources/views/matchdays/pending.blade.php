<x-app-layout>
    <div class="py-6 max-w-2xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 md:p-10 shadow-sm border border-gray-100 dark:border-slate-800 text-center">

            {{-- Ikon dalam lingkaran --}}
            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                <svg class="w-8 h-8 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            {{-- Badge status --}}
            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 rounded-md text-xs font-semibold uppercase tracking-wider mb-4">
                Menunggu Persetujuan
            </span>

            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                Akses Matchday Dikunci
            </h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 leading-relaxed max-w-sm mx-auto mb-8">
                Pendaftaran akun kamu sedang ditinjau admin. Setelah disetujui, kamu langsung bisa melihat dan mendaftar jadwal matchday.
            </p>

            {{-- Indikator tahapan --}}
            <div class="flex items-center justify-center gap-2 mb-8 text-xs">
                <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-semibold">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Registrasi
                </div>
                <div class="w-8 h-px bg-gray-200 dark:bg-slate-700"></div>
                <div class="flex items-center gap-1.5 text-amber-600 dark:text-amber-400 font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Ditinjau Admin
                </div>
                <div class="w-8 h-px bg-gray-200 dark:bg-slate-700"></div>
                <div class="flex items-center gap-1.5 text-gray-400 dark:text-slate-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-slate-700"></span>
                    Akses Terbuka
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-bold transition shadow-sm">
                    Kembali ke Dashboard
                </a>
                <a href="https://wa.me/6289505875530" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-xl text-sm font-bold transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                    </svg>
                    Hubungi Admin
                </a>
            </div>

            <p class="text-xs text-gray-400 dark:text-slate-500 mt-6">
                Mohon untuk menunggu Admin menyetujui pendaftaran akun kamu. <br>
                <span class="text-gray-500 dark:text-slate-400">hubungi manual jika tidak ada kabar dalam 2x24 Jam (+62 895-0587-5530)</span>
            </p>

        </div>

    </div>
</x-app-layout>