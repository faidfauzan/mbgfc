<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto px-4">
        <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-lg text-white">
            
            {{-- Poster Matchday --}}
            <div class="w-full h-72 md:h-96 bg-slate-950 relative">
                <img src="{{ $matchday->poster ? asset('storage/'.$matchday->poster) : asset('images/default-poster.jpg') }}" 
                     alt="{{ $matchday->nama_matchday }}" 
                     class="w-full h-full object-contain">
            </div>

            {{-- Info Detail --}}
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-1 rounded">
                            {{ $matchday->nomor_matchday ?? 'MD' }}
                        </span>
                        <h1 class="text-2xl font-bold mt-2">{{ $matchday->nama_matchday }}</h1>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-400">HTM</span>
                        <p class="text-xl font-bold text-emerald-400">Rp {{ number_format($matchday->htm, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-6 bg-slate-800/50 p-4 rounded-lg border border-slate-700/50">
                    <div>
                        <p class="text-xs text-gray-400">Tanggal & Waktu</p>
                        <p class="font-medium">\ \ {{ \Carbon\Carbon::parse($matchday->tanggal)->translatedFormat('d F Y') }}</p>
                        <p class="text-sm text-gray-300">{{ $matchday->jam_mulai }} - {{ $matchday->jam_selesai }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Lokasi & Kuota</p>
                        <p class="font-medium">\ \ {{ $matchday->lokasi }}</p>
                        <p class="text-sm text-emerald-400">Kuota Utama: {{ $matchday->kuota_peserta }} Orang</p>
                    </div>
                </div>

                @if($matchday->fasilitas)
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 mb-1">Fasilitas</p>
                        <p class="text-sm text-gray-200">{{ is_array($matchday->fasilitas) ? implode(', ', $matchday->fasilitas) : $matchday->fasilitas }}</p>
                    </div>
                @endif

                @if($matchday->catatan)
                    <div class="mb-6">
                        <p class="text-xs text-gray-400 mb-1">Catatan</p>
                        <p class="text-sm text-gray-300 bg-slate-800 p-3 rounded-md">{{ $matchday->catatan }}</p>
                    </div>
                @endif

                {{-- Action Area --}}
                <div class="pt-4 border-t border-slate-800 flex justify-between items-center">
                    <a href="{{ route('matchday.member.index') }}" class="text-gray-400 hover:text-white text-sm">
                        &larr; Kembali ke Daftar
                    </a>

                    <div>
                        @if (!$registration)
                            <a href="{{ route('matchday.member.create-form', $matchday) }}" 
                               class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-6 py-2.5 rounded-lg transition inline-block">
                                Daftar Sekarang
                            </a>
                        @elseif ($registration->status === 'utama')
                            <div class="flex items-center gap-3">
                                <span class="bg-emerald-500/20 text-emerald-400 px-3 py-1.5 rounded-lg text-sm font-medium border border-emerald-500/30">
                                    ✓ Terdaftar (Utama)
                                </span>
                                <form method="POST" action="{{ route('matchday.member.batal', $registration) }}" onsubmit="return confirm('Yakin ingin membatalkan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                                        Batalkan
                                    </button>
                                </form>
                            </div>
                        @elseif ($registration->status === 'waiting_list')
                            <div class="flex items-center gap-3">
                                <span class="bg-amber-500/20 text-amber-400 px-3 py-1.5 rounded-lg text-sm font-medium border border-amber-500/30">
                                    ⏳ Waiting List
                                </span>
                                <form method="POST" action="{{ route('matchday.member.batal', $registration) }}" onsubmit="return confirm('Yakin ingin membatalkan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                                        Batalkan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>