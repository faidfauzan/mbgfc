<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Flash Notification --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Header Status Member Prioritas -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    @if($isPrioritasAktif)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-xs font-semibold uppercase tracking-wider mb-3">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            MEMBER PRIORITAS AKTIF
                        </span>
                        <h1 class="text-2xl font-bold text-gray-800">
                            {{ ucfirst(str_replace('_', ' ', $member->paket_prioritas ?? 'Paket Prioritas')) }}
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Berlaku sampai 
                            <span class="font-semibold text-gray-700">
                                {{ \Carbon\Carbon::parse($member->tanggal_berakhir_prioritas)->translatedFormat('j F Y') }}
                            </span> 
                            &bull; <span class="text-emerald-600 font-bold">{{ $sisaHari }} hari lagi</span>
                        </p>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-wider mb-3">
                            MEMBER REGULER
                        </span>
                        <h1 class="text-2xl font-bold text-gray-800">Belum Berlangganan Prioritas</h1>
                        <p class="text-sm text-gray-500 mt-1">Dapatkan garansi slot main di setiap pertandingan matchday.</p>
                    @endif
                </div>

                <div class="shrink-0">
                    <a href="{{ route('prioritas.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition shadow-sm">
                        {{ $isPrioritasAktif ? 'Perpanjang Sekarang' : 'Daftar Prioritas' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Riwayat Transaksi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h2>
                <p class="text-sm text-gray-500 mt-1">Semua transaksi upgrade dan perpanjangan member prioritas kamu.</p>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-left text-sm text-gray-700 border-collapse">
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-4 whitespace-nowrap">Paket</th>
                            <th class="px-6 py-4 whitespace-nowrap">Periode</th>
                            <th class="px-6 py-4 whitespace-nowrap">Metode</th>
                            <th class="px-6 py-4 whitespace-nowrap">Jumlah</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Status</th>
                            <th class="px-6 py-4 whitespace-nowrap">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-700 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($tx->created_at)->format('j M Y') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                    {{ $tx->paket }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                    @if($tx->periode_mulai && $tx->periode_selesai)
                                        {{ \Carbon\Carbon::parse($tx->periode_mulai)->format('j M') }} &ndash; {{ \Carbon\Carbon::parse($tx->periode_selesai)->format('j M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $tx->metode_pembayaran }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800 whitespace-nowrap">
                                    Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($tx->status === 'lunas')
                                        <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md text-xs font-semibold border border-emerald-200">
                                            Lunas
                                        </span>
                                    @elseif($tx->status === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 rounded-md text-xs font-semibold border border-rose-200">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 rounded-md text-xs font-semibold border border-amber-200">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($tx->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $tx->bukti_pembayaran) }}" target="_blank" class="text-emerald-600 hover:underline font-semibold text-xs">
                                            Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    Kamu belum memiliki riwayat transaksi prioritas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            @endif

        </div>

    </div>
</x-app-layout>