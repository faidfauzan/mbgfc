<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Main Card Container -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Matchday (Selesai)</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar pertandingan yang telah dilaksanakan dan rekap partisipasinya.</p>
            </div>

            <!-- Table Wrapper -->
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-left text-sm text-gray-700 border-collapse">
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 whitespace-nowrap">Matchday</th>
                            <th class="px-6 py-4 whitespace-nowrap">Tanggal & Waktu</th>
                            <th class="px-6 py-4 whitespace-nowrap">Lokasi</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Peserta Utama</th>
                            <th class="px-6 py-4 whitespace-nowrap">Estimasi HTM Terkumpul</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($matchdays as $match)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                {{ $match->nama_matchday }} 
                                <span class="text-xs text-gray-400 font-normal">({{ $match->nomor_matchday }})</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($match->tanggal)->format('d M Y') }} | {{ $match->jam_mulai }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $match->lokasi }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold border border-emerald-200">
                                    {{ $match->total_utama }} / {{ $match->kuota }} Player
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-emerald-600 whitespace-nowrap">
                                Rp {{ number_format($match->total_utama * $match->htm, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('matchdays.peserta', $match->id) }}" class="inline-flex items-center text-xs bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-md font-medium transition shadow-sm">
                                    Lihat Peserta
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                Belum ada matchday yang selesai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($matchdays->hasPages())
            <div class="mt-6">
                {{ $matchdays->links() }}
            </div>
            @endif

        </div>
    </div>
</x-app-layout>