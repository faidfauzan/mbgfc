<x-app-layout>
    <div class="p-6 bg-slate-900 min-h-screen text-white">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Riwayat Pertandingan Member</h2>
                <p class="text-sm text-gray-400">Nama Member: <span class="text-emerald-400 font-semibold">{{ $user->name }}</span></p>
            </div>
            <a href="{{ url()->previous() }}" class="text-xs bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-xl transition">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-slate-800 rounded-2xl p-4 overflow-x-auto shadow-xl border border-slate-700">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-slate-700/50 text-gray-100 uppercase text-xs">
                    <tr>
                        <th class="p-3">Matchday</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Posisi</th>
                        <th class="p-3">Status Keikutsertaan</th>
                        <th class="p-3">Metode Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($registrations as $reg)
                    <tr class="hover:bg-slate-700/30">
                        <td class="p-3 font-medium">{{ $reg->matchday->nama_matchday ?? '-' }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($reg->matchday->tanggal ?? now())->format('d M Y') }}</td>
                        <td class="p-3 uppercase text-xs font-bold text-indigo-400">{{ $reg->posisi }}</td>
                        <td class="p-3">
                            @if($reg->status == 'utama')
                                <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-semibold">Utama</span>
                            @elseif($reg->status == 'waiting_list')
                                <span class="px-2.5 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold">Waiting List</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-semibold">Batal</span>
                            @endif
                        </td>
                        <td class="p-3 uppercase text-xs font-medium text-gray-300">{{ $reg->metode_pembayaran ?? 'Cash' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-400">Member ini belum pernah mendaftar matchday.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>