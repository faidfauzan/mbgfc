<x-app-layout>
    <div class="py-6 max-w-2xl mx-auto ">

        <div
            class="bg-white dark:bg-slate-900 rounded-2xl p-8 md:p-10 shadow-sm border border-gray-100 dark:border-slate-800 text-center">

            {{-- Ikon dalam lingkaran --}}
            <div
                class="w-16 h-16 mx-auto mb-5 rounded-full bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center">
                <svg class="w-8 h-8 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
                    <line x1="12" y1="2" x2="12" y2="12" />
                </svg>
            </div>

            {{-- Badge status --}}
            <span
                class="inline-flex items-center px-2.5 py-1 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 rounded-md text-xs font-semibold uppercase tracking-wider mb-4">
                Akun Dinonaktifkan
            </span>

            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                Akses Dibatasi
            </h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 leading-relaxed max-w-sm mx-auto mb-8">
                Akun kamu telah dinonaktifkan oleh admin. Kamu tidak bisa mengakses jadwal matchday maupun berlangganan
                prioritas untuk sementara waktu.
            </p>

            {{-- Indikator status --}}
            <div class="flex items-center justify-center gap-2 mb-8 text-xs">
                <div class="flex items-center gap-1.5 text-gray-400 dark:text-slate-600">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Terdaftar
                </div>
                <div class="w-8 h-px bg-gray-200 dark:bg-slate-700"></div>
                <div class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400 font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    Dinonaktifkan
                </div>
                <div class="w-8 h-px bg-gray-200 dark:bg-slate-700"></div>
                <div class="flex items-center gap-1.5 text-gray-400 dark:text-slate-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-slate-700"></span>
                    Akses Terbuka
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-xl text-sm font-bold transition">
                    Kembali ke Dashboard
                </a>
                <a href="https://wa.me/6289505875530" target="_blank"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                    </svg>
                    Hubungi Admin
                </a>
            </div>

            <p class="text-xs text-gray-400 dark:text-slate-500 mt-6">
                Hubungi admin untuk informasi lebih lanjut mengenai pengaktifan kembali akun kamu.
            </p>

        </div>

    </div>
</x-app-layout>