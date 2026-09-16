<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Matchday') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold mb-6 text-gray-800 border-b pb-3">Daftar Matchday yang Dibuka</h3>

                    @if($matchdays->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada matchday yang berstatus open saat ini.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($matchdays as $matchday)
                                @php
                                    $reg = $registrations[$matchday->id] ?? null;
                                @endphp

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
                                                <span
                                                    class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">
                                                    {{ $matchday->nomor_matchday ?? 'MD' }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-600 mb-4">
                                                <p><span class="font-semibold text-gray-700">Tanggal:</span>
                                                    {{ is_object($matchday->tanggal) ? $matchday->tanggal->format('d M Y') : $matchday->tanggal }}
                                                </p>
                                                <p><span class="font-semibold text-gray-700">Jam:</span>
                                                    {{ $matchday->jam_mulai ?? $matchday->jam ?? '-' }} -
                                                    {{ $matchday->jam_selesai ?? '' }}</p>
                                                <p><span class="font-semibold text-gray-700">Lokasi:</span>
                                                    {{ $matchday->lokasi ?? '-' }}</p>

                                                {{-- HTM GK & Player --}}
                                                <p><span class="font-semibold text-gray-700">HTM:</span> GK Rp
                                                    {{ number_format($matchday->htm_gk, 0, ',', '.') }} | Player Rp
                                                    {{ number_format($matchday->htm_player, 0, ',', '.') }}</p>

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
                                            @if(!$member)
                                                <span class="text-red-500 text-sm font-medium">Akun Anda belum terhubung ke data
                                                    member.</span>
                                            @else
                                                <div class="flex items-center gap-3">
                                                    {{-- Tombol Info Match --}}
                                                    <a href="{{ route('matchday.member.show', $matchday) }}"
                                                        class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                                                        Info Match
                                                    </a>

                                                    {{-- Badge Status Ringkas (Jika Sudah Daftar) --}}
                                                    @if($reg)
                                                        @if($reg->status === 'utama')
                                                            <span
                                                                class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-md">
                                                                ✓ Terdaftar
                                                            </span>
                                                        @elseif($reg->status === 'waiting_list')
                                                            <span
                                                                class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-md">
                                                                ⏳ Waiting List
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
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