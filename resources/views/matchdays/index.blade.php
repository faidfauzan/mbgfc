<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Matchday') }}
        </h2>
    </x-slot>

    <div class="pb-6">
        <div class="max-w-7xl mx-auto ">

            @if(session('success'))
                <div class="mb-4 text-emerald-800 font-medium bg-emerald-100 p-3 rounded-lg border border-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-200">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6 border-b pb-3">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Matchday</h3>

                        <a href="{{ route('matchdays.create') }}"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition whitespace-nowrap text-sm">
                            + Buat Matchday
                        </a>
                    </div>

                    @if($matchdays->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada matchday.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($matchdays as $matchday)
                                <!-- CARD MATCHDAY -->
                                <div
                                    class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition flex flex-col md:flex-row">

                                    <!-- Poster Matchday (Kiri) -->
                                    <div class="md:w-1/3 lg:w-1/4 bg-gray-100 flex items-center justify-center min-h-[220px]">
                                        @if($matchday->poster)
                                            <img src="{{ asset('storage/' . $matchday->poster) }}"
                                                alt="Poster {{ $matchday->nama_matchday ?? $matchday->nama }}"
                                                class="w-full h-full object-cover max-h-[260px]">
                                        @else
                                            <div class="text-center p-6 text-gray-400">
                                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs font-semibold uppercase tracking-wider">Tanpa Poster</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Detail Matchday (Kanan) -->
                                    <div class="p-6 md:w-2/3 lg:w-3/4 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-bold text-xl text-gray-900">
                                                    {{ $matchday->nama_matchday ?? $matchday->nama ?? 'Matchday #' . $matchday->id }}
                                                </h4>
                                                <div class="flex flex-col items-end gap-1">
                                                    <span
                                                        class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">
                                                        {{ $matchday->nomor_matchday ?? 'MD' }}
                                                    </span>
                                                    @if(strtolower($matchday->status) === 'open')
                                                        <span
                                                            class="px-2.5 py-1 text-xs rounded-full font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                            OPEN
                                                        </span>
                                                    @elseif(strtolower($matchday->status) === 'closed')
                                                        <span
                                                            class="px-2.5 py-1 text-xs rounded-full font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                                                            CLOSED
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2.5 py-1 text-xs rounded-full font-semibold bg-slate-100 text-slate-800 border border-slate-300">
                                                            {{ strtoupper($matchday->status) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-600 mb-4">
                                                <p><span class="font-semibold text-gray-700">Tanggal:</span>
                                                    {{ \Carbon\Carbon::parse($matchday->tanggal)->format('d M Y') }}
                                                </p>
                                                <p><span class="font-semibold text-gray-700">Jam:</span>
                                                    {{ $matchday->jam_mulai ? \Carbon\Carbon::parse($matchday->jam_mulai)->format('H:i') : '-' }}
                                                    -
                                                    {{ $matchday->jam_selesai ? \Carbon\Carbon::parse($matchday->jam_selesai)->format('H:i') : '' }}
                                                </p>
                                                <p><span class="font-semibold text-gray-700">Lokasi:</span>
                                                    {{ $matchday->lokasi ?? '-' }}
                                                </p>

                                                {{-- HTM GK & Player --}}
                                                <p><span class="font-semibold text-gray-700">HTM:</span> GK Rp
                                                    {{ number_format($matchday->htm_gk, 0, ',', '.') }} | Player Rp
                                                    {{ number_format($matchday->htm_player, 0, ',', '.') }}
                                                </p>

                                                {{-- Kuota GK & Player --}}
                                                <p>
                                                    <span class="font-semibold text-gray-700">Kuota Posisi:</span>
                                                    <span
                                                        class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded font-semibold">GK:
                                                        {{ $matchday->kuota_gk ?? 0 }}</span>
                                                    <span
                                                        class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-semibold ml-1">Player:
                                                        {{ $matchday->kuota_player ?? 0 }}</span>
                                                </p>

                                                @if(!empty($matchday->fasilitas))
                                                    <p class="sm:col-span-2"><span
                                                            class="font-semibold text-gray-700">Fasilitas:</span>
                                                        {{ is_array($matchday->fasilitas) ? implode(', ', $matchday->fasilitas) : $matchday->fasilitas }}
                                                    </p>
                                                @endif
                                            </div>

                                            @if($matchday->catatan)
                                                <p class="text-xs text-gray-500 bg-gray-50 p-2 rounded border border-gray-100 mb-4">
                                                    <span class="font-semibold">Catatan:</span> {{ $matchday->catatan }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Tombol Aksi -->
                                        <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('matchdays.peserta', $matchday->id) }}"
                                                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                                                    Peserta
                                                </a>
                                                <a href="{{ route('matchdays.edit', $matchday->id) }}"
                                                    class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('matchdays.destroy', $matchday->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>