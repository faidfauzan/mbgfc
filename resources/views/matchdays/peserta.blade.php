<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Peserta Matchday') }}
            </h2>
            <a href="{{ route('matchdays.index') }}"
                class="text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition">
                &larr; Kembali ke List Matchday
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <!-- RINGKASAN MATCHDAY -->
            <div
                class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $matchday->judul ?? 'Matchday #' . $matchday->id }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        Tanggal: <span
                            class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($matchday->tanggal)->format('d M Y, H:i') }}</span>
                        |
                        Lokasi: <span class="font-medium text-gray-800">{{ $matchday->lokasi }}</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">
                        Kuota Utama: {{ $matchday->kuota_utama }}
                    </span>
                </div>
            </div>

            <!-- TABEL PESERTA -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-max text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs uppercase font-semibold text-gray-600">
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Peserta</th>
                                <th class="p-3">Email</th>
                                <th class="p-3">No. HP</th>
                                <th class="p-3">Jenis Member</th>
                                <th class="p-3">Waktu Daftar</th>
                                <th class="p-3">Status Skuad</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($registrations as $index => $reg)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-medium text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-3 font-semibold text-gray-900">{{ $reg->member->user->name ?? '-' }}</td>
                                    <td class="p-3 text-gray-600">{{ $reg->member->user->email ?? '-' }}</td>
                                    <td class="p-3 text-gray-700">{{ $reg->member->no_hp ?? '-' }}</td>
                                    <td class="p-3 capitalize">
                                        <span
                                            class="px-2 py-0.5 text-xs rounded font-medium {{ $reg->member->jenis_member === 'prioritas' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $reg->member->jenis_member }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-500 text-xs">
                                        {{ \Carbon\Carbon::parse($reg->waktu_daftar)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="p-3">
                                        @if ($reg->status === 'utama')
                                            <span
                                                class="px-2.5 py-1 text-xs rounded-full font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                Utama
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs rounded-full font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                                                Waiting List
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        <form action="{{ route('matchdays.batalkan-paksa', $reg) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran member ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-rose-600 hover:text-rose-900 font-semibold text-xs bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-md transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-4 text-center text-gray-500">Belum ada peserta yang mendaftar
                                        di matchday ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>