<x-app-layout>
    <div class="p-6 bg-slate-900 min-h-screen text-white">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Riwayat Matchday (Selesai)</h2>
                <p class="text-sm text-gray-400">Daftar pertandingan yang telah dilaksanakan dan rekap partisipasinya.</p>
            </div>
        </div>

        <div class="bg-slate-800 rounded-2xl p-4 overflow-x-auto shadow-xl border border-slate-700">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-slate-700/50 text-gray-100 uppercase text-xs">
                    <tr>
                        <th class="p-3">Matchday</th>
                        <th class="p-3">Tanggal & Waktu</th>
                        <th class="p-3">Lokasi</th>
                        <th class="p-3">Peserta Utama</th>
                        <th class="p-3">Estimasi HTM Terkumpul</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($matchdays as $match)
                    <tr class="hover:bg-slate-700/30">
                        <td class="p-3 font-semibold text-emerald-400">
                            {{ $match->nama_matchday }} <span class="text-xs text-gray-400">({{ $match->nomor_matchday }})</span>
                        </td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($match->tanggal)->format('d M Y') }} | {{ $match->jam_mulai }}</td>
                        <td class="p-3">{{ $match->lokasi }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 rounded-lg text-xs font-medium">
                                {{ $match->total_utama }} / {{ $match->kuota }} Player
                            </span>
                        </td>
                        <td class="p-3 font-bold text-yellow-400">
                            Rp {{ number_format($match->total_utama * $match->htm, 0, ',', '.') }}
                        </td>
                        <td class="p-3">
                            <a href="{{ route('matchdays.peserta', $match->id) }}" class="text-xs bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1.5 rounded-xl transition">
                                Lihat Peserta
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-400">Belum ada matchday yang selesai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $matchdays->links() }}
            </div>
        </div>
    </div>
</x-app-layout>