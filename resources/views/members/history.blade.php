<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Cards Statistik Member -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase">Total Matchday Diikuti</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalMatchdays }} Main</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase">Total HTM Terbayar</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalHtm, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase">Status Keanggotaan</p>
                <span class="inline-block mt-2 px-3 py-1 bg-emerald-50 text-emerald-700 font-semibold text-xs rounded-lg border border-emerald-200">
                    {{ strtoupper(auth()->user()->member->status_prioritas ?? 'Umum') }}
                </span>
            </div>
        </div>

        <!-- Tabel Riwayat Pertandingan & Pembayaran -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Pertandingan & Pembayaran</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar pertandingan yang pernah kamu ikuti beserta rincian pembayarannya.</p>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-left text-sm text-gray-700 border-collapse">
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 whitespace-nowrap">Matchday</th>
                            <th class="px-6 py-4 whitespace-nowrap">Tanggal & Waktu</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Posisi Main</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Status Kehadiran</th>
                            <th class="px-6 py-4 whitespace-nowrap">Biaya HTM</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Status Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($history as $reg)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                {{ $reg->matchday->nama_matchday ?? 'Matchday' }}
                                <span class="text-xs text-gray-400 font-normal">({{ $reg->matchday->nomor_matchday ?? '-' }})</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($reg->matchday->tanggal)->format('d M Y') }} | {{ $reg->matchday->jam_mulai }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-semibold">
                                    {{ strtoupper($reg->posisi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if(strtolower($reg->status) === 'utama')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md text-xs font-semibold border border-emerald-200">Utama</span>
                                @elseif(strtolower($reg->status) === 'waiting_list')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 rounded-md text-xs font-semibold border border-amber-200">Waiting List</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 rounded-md text-xs font-semibold border border-rose-200">Batal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800 whitespace-nowrap">
                                Rp {{ number_format($reg->matchday->htm ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-semibold border border-blue-200">
                                    Lunas
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                Kamu belum memiliki riwayat pertandingan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($history->hasPages())
            <div class="mt-6">
                {{ $history->links() }}
            </div>
            @endif
        </div>

    </div>
</x-app-layout>