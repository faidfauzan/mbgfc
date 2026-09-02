<x-app-layout>
    {{-- Inisialisasi Alpine.js state di paling luar --}}
    <div class="py-8 max-w-4xl mx-auto px-4" x-data="{ openPesertaModal: false }">
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
                        <p class="font-medium">{{ \Carbon\Carbon::parse($matchday->tanggal)->translatedFormat('d F Y') }}</p>
                        <p class="text-sm text-gray-300">{{ $matchday->jam_mulai }} - {{ $matchday->jam_selesai }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Lokasi & Kuota Posisi</p>
                        <p class="font-medium mb-1">{{ $matchday->lokasi }}</p>
                        
                        <div class="flex gap-4 text-sm mt-1">
                            <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2.5 py-0.5 rounded text-xs font-semibold">
                                GK: {{ $matchday->kuota_gk }} Orang
                            </span>
                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2.5 py-0.5 rounded text-xs font-semibold">
                                Player: {{ $matchday->kuota_player }} Orang
                            </span>
                        </div>
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
                <div class="pt-4 border-t border-slate-800 flex flex-wrap justify-between items-center gap-4">
                    <a href="{{ route('matchday.member.index') }}" class="text-gray-400 hover:text-white text-sm">
                        &larr; Kembali ke Daftar
                    </a>

                    <div class="flex items-center gap-3">
                        {{-- Tombol Lihat Peserta (Selalu Tampil) --}}
                        <button type="button" @click="openPesertaModal = true" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-medium rounded-lg text-sm transition flex items-center gap-2 border border-slate-700">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Peserta ({{ $matchday->registrations->count() }})
                        </button>

                        {{-- Status & Action Pendaftaran --}}
                        @if (!$registration)
                            <a href="{{ route('matchday.member.create-form', $matchday) }}" 
                               class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-6 py-2 rounded-lg transition inline-block text-sm">
                                Daftar Sekarang
                            </a>
                        @elseif ($registration->status === 'utama')
                            <span class="bg-emerald-500/20 text-emerald-400 px-3 py-1.5 rounded-lg text-sm font-medium border border-emerald-500/30">
                                ✓ Terdaftar (Utama - {{ $registration->posisi === 'kiper' ? 'GK' : 'PLAYER' }})
                            </span>
                            <form method="POST" action="{{ route('matchday.member.batal', $registration) }}" onsubmit="return confirm('Yakin ingin membatalkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                                    Batalkan
                                </button>
                            </form>
                        @elseif ($registration->status === 'waiting_list')
                            <span class="bg-amber-500/20 text-amber-400 px-3 py-1.5 rounded-lg text-sm font-medium border border-amber-500/30">
                                ⏳ Waiting List ({{ $registration->posisi === 'kiper' ? 'GK' : 'PLAYER' }})
                            </span>
                            <form method="POST" action="{{ route('matchday.member.batal', $registration) }}" onsubmit="return confirm('Yakin ingin membatalkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal Popup Lihat Peserta --}}
<div x-show="openPesertaModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div @click.away="openPesertaModal = false" class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white">Daftar Peserta</h3>
            <button @click="openPesertaModal = false" class="text-slate-400 hover:text-white text-xl font-bold">&times;</button>
        </div>

        <div class="mt-4 max-h-80 overflow-y-auto space-y-3 pr-1">
            @forelse($matchday->registrations as $reg)
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-800/60 border border-slate-700/50">
                    <div class="flex items-center gap-3">
                        {{-- Ambil avatar dari member -> user --}}
                        @if($reg->member->user->avatar ?? false)
                            <img src="{{ asset('storage/' . $reg->member->user->avatar) }}" class="w-9 h-9 rounded-full object-cover">
                        @else
                            <div class="w-9 h-9 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($reg->member->user->name ?? $reg->member->name ?? 'U', 0, 2)) }}
                            </div>
                        @endif

                        <div>
                            {{-- Ambil nama dari member -> user --}}
                            <p class="text-sm font-semibold text-white">{{ $reg->member->user->name ?? $reg->member->name ?? 'Member' }}</p>
                            <p class="text-xs text-slate-400 uppercase font-medium">Posisi: {{ $reg->posisi === 'kiper' ? 'GK' : 'Player' }}</p>
                        </div>
                    </div>

                    <div>
                        @if($reg->status === 'utama')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                Utama
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                Waiting List
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-sm text-slate-400 py-6">Belum ada peserta yang mendaftar.</p>
            @endforelse
        </div>
    </div>
</div>

    </div>
</x-app-layout>