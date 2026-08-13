<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Matchday') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-4 sm:p-6">

                @if(session('success'))
                    <div class="mb-4 text-emerald-800 font-medium bg-emerald-100 p-3 rounded-lg border border-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Matchday</h3>
                    
                    <!-- TOMBOL TAMBAH MATCHDAY (Hijau Emerald MBG FC) -->
                    <a href="{{ route('matchdays.create') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition whitespace-nowrap text-sm">
                        + Buat Matchday
                    </a>
                </div>

                <!-- TABLE MATCHDAY -->
                <div class="w-full overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full min-w-max text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">No. Matchday</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Nama / Judul</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Jadwal</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Lokasi</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Fasilitas</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">HTM</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Kuota</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100 text-sm">
                            @forelse($matchdays as $matchday)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-800">{{ $matchday->nomor_matchday }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $matchday->nama_matchday }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($matchday->tanggal)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ $matchday->jam_mulai ? \Carbon\Carbon::parse($matchday->jam_mulai)->format('H:i') : '-' }} - 
                                            {{ $matchday->jam_selesai ? \Carbon\Carbon::parse($matchday->jam_selesai)->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $matchday->lokasi }}</td>
                                    <td class="px-4 py-3 min-w-[200px]">
                                        @if(!empty($matchday->fasilitas) && is_array($matchday->fasilitas))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($matchday->fasilitas as $item)
                                                    <span class="inline-flex items-center bg-blue-100 text-blue-800 text-[11px] font-medium px-2 py-0.5 rounded whitespace-nowrap">
                                                        {{ $item }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($matchday->htm == 0)
                                            <span class="text-emerald-600 font-semibold">Gratis</span>
                                        @else
                                            <span class="text-slate-700 font-medium">Rp {{ number_format($matchday->htm, 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $matchday->kuota }} orang</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <!-- BADGE STATUS MATCHDAY -->
                                        @if(strtolower($matchday->status) === 'open')
                                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                OPEN
                                            </span>
                                        @elseif(strtolower($matchday->status) === 'closed')
                                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                                                CLOSED
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold bg-slate-100 text-slate-800 border border-slate-300">
                                                {{ strtoupper($matchday->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <a href="{{ route('matchdays.edit', $matchday->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs bg-indigo-50 px-3 py-1.5 rounded-md">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-slate-400">Belum ada matchday yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>