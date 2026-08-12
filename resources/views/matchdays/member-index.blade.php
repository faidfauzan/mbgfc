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
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Daftar Matchday yang Dibuka</h3>

                    @if($matchdays->isEmpty())
                        <p class="text-gray-500">Belum ada matchday yang berstatus open saat ini.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($matchdays as $matchday)
                                @php
                                    $reg = $registrations[$matchday->id] ?? null;
                                @endphp

                                <div class="border p-4 rounded-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div>
                                        <h4 class="font-bold text-lg">{{ $matchday->nama ?? 'Matchday #' . $matchday->id }}</h4>
                                        <p class="text-sm text-gray-600">Tanggal: {{ $matchday->tanggal }} | Jam: {{ $matchday->jam ?? '-' }}</p>
                                        <p class="text-sm text-gray-600">Lokasi: {{ $matchday->lokasi ?? '-' }}</p>
                                        <p class="text-sm text-gray-600">HTM: Rp {{ number_format($matchday->htm, 0, ',', '.') }} | Kuota Utama: {{ $matchday->kuota_peserta }}</p>
                                    </div>

                                    <div>
                                        @if(!$member)
                                            <span class="text-red-500 text-sm">Akun Anda belum terhubung ke data member.</span>
                                        @elseif(!$reg)
                                            <!-- Tombol Daftar -->
                                            <form method="POST" action="{{ route('matchday.member.daftar', $matchday) }}">
                                                @csrf
                                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                                    Daftar Sekarang
                                                </button>
                                            </form>
                                        @else
                                            <!-- Status & Tombol Batalkan -->
                                            <div class="flex items-center gap-3">
                                                @if($reg->status === 'utama')
                                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1.5 rounded">
                                                        Terdaftar (Utama)
                                                    </span>
                                                @elseif($reg->status === 'waiting_list')
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1.5 rounded">
                                                        Waiting List
                                                    </span>
                                                @endif

                                                <form method="POST" action="{{ route('matchday.member.batal', $reg) }}" onsubmit="return confirm('Yakin ingin membatalkan pendaftaran?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded text-sm hover:bg-red-600 transition">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
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