<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Member: {{ $member->user->name ?? $member->name }}
            </h2>
            <a href="{{ route('members.index') }}"
                class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Card Informasi Member -->
        <div
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-6 items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $member->user->name ?? $member->name }}</h3>
                <p class="text-sm text-gray-500">{{ $member->user->email ?? '-' }} | {{ $member->no_hp ?? '-' }}</p>
            </div>
            <div class="flex gap-2">
                <span
                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $member->jenis_member === 'prioritas' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($member->jenis_member) }}
                </span>
                <span
                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $member->status_aktif ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $member->status_aktif ? 'Aktif' : 'Non-Aktif' }}
                </span>
            </div>
        </div>

        <!-- Tabel Riwayat Matchday -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h4 class="font-bold text-gray-800">Riwayat Matchday yang Diikuti</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3">Matchday</th>
                            <th class="px-6 py-3">Tanggal & Waktu</th>
                            <th class="px-6 py-3">Posisi</th>
                            <th class="px-6 py-3">Status Slot</th>
                            <th class="px-6 py-3">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse ($member->matchdayRegistrations as $reg)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $reg->matchday->title ?? $reg->matchday->lokasi ?? 'Matchday #' . $reg->matchday_id }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($reg->matchday->tanggal ?? $reg->matchday->created_at ?? now())->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Membaca atribut posisi/role/position --}}
                                    @php
                                        $posisi = $reg->posisi ?? $reg->position ?? $reg->role ?? '-';
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-md text-xs font-semibold {{ strtoupper($posisi) === 'GK' ? 'bg-cyan-100 text-cyan-800' : 'bg-slate-100 text-slate-800' }}">
                                        {{ strtoupper($posisi) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Menyesuaikan Badge Warna Berdasarkan Status --}}
                                    @php
                                        $status = strtolower($reg->status ?? 'utama');
                                        $badgeStyle = match ($status) {
                                            'utama' => 'bg-emerald-100 text-emerald-800',
                                            'waiting_list', 'waiting' => 'bg-amber-100 text-amber-800',
                                            'batal', 'cancelled' => 'bg-rose-100 text-rose-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $badgeStyle }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $reg->created_at ? $reg->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                    Member ini belum pernah mendaftar matchday apa pun.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>