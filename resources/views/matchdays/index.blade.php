<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Matchday') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 text-green-600 font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Daftar Matchday ({{ $matchdays->total() }})</h3>
                    <a href="{{ route('matchdays.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        + Tambah Matchday
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Matchday</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama / Judul</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fasilitas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">HTM</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kuota</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($matchdays as $matchday)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap font-semibold">{{ $matchday->nomor_matchday }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $matchday->nama_matchday }}</td>
                                    
                                    {{-- Format Jadwal: Tanggal + Jam Mulai s/d Jam Selesai --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($matchday->tanggal)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $matchday->jam_mulai ? \Carbon\Carbon::parse($matchday->jam_mulai)->format('H:i') : '-' }} 
                                            - 
                                            {{ $matchday->jam_selesai ? \Carbon\Carbon::parse($matchday->jam_selesai)->format('H:i') : '-' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">{{ $matchday->lokasi }}</td>
                                    
                                    {{-- Format Fasilitas (Layout Kesamping / Horizontal dengan batas lebar kolom) --}}
                                    <td class="px-4 py-3 min-w-[200px] max-w-[280px]">
                                        @if(!empty($matchday->fasilitas) && is_array($matchday->fasilitas))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($matchday->fasilitas as $item)
                                                    <span class="inline-flex items-center bg-blue-100 text-blue-800 text-[11px] font-medium px-2 py-0.5 rounded whitespace-nowrap">
                                                        {{ $item }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($matchday->htm == 0)
                                            <span class="text-green-600 font-semibold">Gratis</span>
                                        @else
                                            Rp {{ number_format($matchday->htm, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $matchday->kuota }} orang</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                                            {{ $matchday->status === 'open' ? 'bg-green-100 text-green-800' : ($matchday->status === 'closed' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ strtoupper($matchday->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('matchdays.edit', $matchday->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-4 text-center text-gray-500">Belum ada matchday yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $matchdays->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>