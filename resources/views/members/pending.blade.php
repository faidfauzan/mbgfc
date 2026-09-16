<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Flash Notification Sukses --}}
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 p-4 rounded-2xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Flash Notification Error / Penolakan --}}
        @if(session('error'))
            <div class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 p-4 rounded-2xl text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Persetujuan Member Baru</h2>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                        Member yang mendaftar harus disetujui sebelum bisa mengakses jadwal matchday.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 rounded-md text-xs font-semibold uppercase tracking-wider w-fit">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $pendingMembers->count() }} menunggu
                </span>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-800">
            <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-slate-800">
                <table class="w-full text-left text-sm text-gray-700 dark:text-slate-300 border-collapse">
                    <thead class="bg-gray-50 dark:bg-slate-800/60 text-xs uppercase font-semibold text-gray-500 dark:text-slate-400 border-b border-gray-100 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 whitespace-nowrap">Member</th>
                            <th class="px-6 py-4 whitespace-nowrap">Email</th>
                            <th class="px-6 py-4 whitespace-nowrap">No. HP</th>
                            <th class="px-6 py-4 whitespace-nowrap">Tanggal Daftar</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 bg-white dark:bg-slate-900">
                        @forelse($pendingMembers as $user)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-semibold text-xs shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-slate-300 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-slate-300 whitespace-nowrap">{{ $user->phone ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500 dark:text-slate-400 whitespace-nowrap">{{ $user->created_at->format('d M Y, H:i') }}</td>
<td class="px-6 py-4 text-center whitespace-nowrap">
    <div class="inline-flex items-center gap-2">
        {{-- Tombol Setuju --}}
        <form action="{{ route('members.approve', $user->id) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-xs rounded-lg transition">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
                    <line x1="12" y1="2" x2="12" y2="12" />
                </svg>
                Accept
            </button>
        </form>

        {{-- Tombol Tolak --}}
        <form action="{{ route('members.reject', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran akun {{ $user->name }}?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 dark:bg-rose-500/10 hover:bg-rose-500 dark:hover:bg-rose-600 border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 hover:text-white dark:hover:text-white font-semibold text-xs rounded-lg transition">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
                Reject
            </button>
        </form>
    </div>
</td>                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400 dark:text-slate-500">
                                        <svg class="w-10 h-10 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        <p class="font-medium">Tidak ada permintaan pendaftaran baru</p>
                                        <p class="text-xs">Semua member sudah diproses.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>