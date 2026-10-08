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

    <div class="pb-6">
        <div class="max-w-7xl mx-auto space-y-6">

            @if (session('success'))
                <div class="p-4 bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-rose-100 text-rose-800 rounded-lg border border-rose-300">
                    {{ session('error') }}
                </div>
            @endif

            <!-- RINGKASAN MATCHDAY & TOMBOL TAMBAH MEMBER -->
            <div
                class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Matchday {{ $matchday->nomor_matchday ?? 'MD-' . $matchday->id }}
                        <span class="text-gray-500 font-normal">({{ $matchday->nama_matchday }})</span>
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Tanggal: <span
                            class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($matchday->tanggal)->format('d M Y') }},
                            {{ $matchday->jam_mulai ?? '' }}</span>
                        |
                        Lokasi: <span class="font-medium text-gray-800">{{ $matchday->lokasi }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span
                        class="px-3 py-1.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">
                        Kuota Utama: {{ $matchday->kuota ?? ($matchday->kuota_gk + $matchday->kuota_player) }}
                    </span>

                    <!-- TOMBOL BUKA MODAL TAMBAH MEMBER -->
                    <button type="button" onclick="openModalTambahMember()"
                        class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Tambah Member</span>
                    </button>
                </div>
            </div>

            <!-- TABEL PESERTA -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table id="tabel_auto_4" class="w-full min-w-max text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs uppercase font-semibold text-gray-600">
                                <th class="p-3">No</th>
                                <th class="p-3">Nama Peserta</th>
                                <th class="p-3">Email</th>
                                <th class="p-3">No. HP</th>
                                <th class="p-3">Posisi</th>
                                <th class="p-3">Jenis Member</th>
                                <th class="p-3">Waktu Daftar</th>
                                <th class="p-3">Status Skuad</th>
                                <th class="p-3">Pembayaran</th>
                                <th class="p-3 text-center no-export">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @foreach ($registrations as $index => $reg)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-medium text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-3 font-semibold text-gray-900">{{ $reg->member->user->name ?? '-' }}</td>
                                    <td class="p-3 text-gray-600">{{ $reg->member->user->email ?? '-' }}</td>
                                    <td class="p-3 text-gray-700">{{ $reg->member->no_hp ?? '-' }}</td>
                                    <td class="p-3">
                                        <span
                                            class="px-2 py-0.5 text-xs rounded font-medium {{ in_array(strtolower($reg->posisi), ['kiper', 'gk']) ? 'bg-cyan-100 text-cyan-800 border border-cyan-300' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                            {{ in_array(strtolower($reg->posisi), ['kiper', 'gk']) ? 'Kiper (GK)' : 'Pemain' }}
                                        </span>
                                    </td>
                                    <td class="p-3 capitalize">
                                        <span
                                            class="px-2 py-0.5 text-xs rounded font-medium {{ strtolower($reg->tipe_member_saat_daftar) === 'prioritas' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $reg->tipe_member_saat_daftar }}
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
                                    <td class="p-3">
                                        @if ($reg->metode_pembayaran === 'qris')
                                            <div class="flex flex-col gap-1 items-start">
                                                <span
                                                    class="px-2 py-0.5 text-xs rounded font-medium bg-indigo-100 text-indigo-800 border border-indigo-300">QRIS</span>
                                                @if ($reg->bukti_bayar)
                                                    <a href="{{ asset('storage/' . $reg->bukti_bayar) }}" target="_blank"
                                                        class="text-[10px] text-blue-600 hover:text-blue-800 underline">Lihat
                                                        Bukti</a>
                                                @endif
                                            </div>
                                        @else
                                            <span
                                                class="px-2 py-0.5 text-xs rounded font-medium bg-slate-100 text-slate-700 border border-slate-300">Cash</span>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL POP-UP DAFTAR MEMBER -->
    <div id="modalTambahMember" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg border border-gray-200 overflow-hidden">
            <!-- Header Modal -->
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-900">Pilih Member MBG FC</h3>
                <button type="button" onclick="closeModalTambahMember()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Body Modal -->
            <div class="p-6">
                <!-- Input Search Frontend -->
                <div class="mb-4">
                    <input type="text" id="searchMemberInput" onkeyup="filterMembers()" 
                           placeholder="Cari nama member..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- List Member -->
                <div class="max-h-72 overflow-y-auto space-y-2 pr-1" id="memberList">
                    @forelse($allMembers as $m)
                        <div class="member-item flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition" 
                             data-name="{{ strtolower($m->user->name ?? '') }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center font-bold text-white text-xs shadow-sm">
                                    {{ strtoupper(substr($m->user->name ?? 'M', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">{{ $m->user->name ?? 'Member' }}</p>
                                    <span class="text-[11px] px-2 py-0.5 rounded bg-gray-200 text-gray-700 font-medium">
                                        Tipe: {{ ucfirst($m->tipe_member ?? 'Umum') }}
                                    </span>
                                </div>
                            </div>
                            <!-- Tombol (+) mengarah ke Form Pendaftaran Admin -->
                            <a href="{{ route('matchday.admin.register-member', ['matchday' => $matchday->id, 'member' => $m->id]) }}" 
                               title="Daftarkan Member Ini"
                               class="p-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-6 text-sm">
                            <p>Semua member sudah terdaftar di matchday ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT MODAL & INSTANT SEARCH FRONTEND -->
    <script>
        function openModalTambahMember() {
            document.getElementById('modalTambahMember').classList.remove('hidden');
        }

        function closeModalTambahMember() {
            document.getElementById('modalTambahMember').classList.add('hidden');
        }

        function filterMembers() {
            let input = document.getElementById('searchMemberInput').value.toLowerCase();
            let items = document.querySelectorAll('.member-item');

            items.forEach(item => {
                let name = item.getAttribute('data-name');
                if (name.includes(input)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>

    <x-datatables id="tabel_auto_4" :export="true" exportName="Data Peserta Matchday {{ $matchday->nomor_matchday ?? 'MD-'.$matchday->id }} ({{ \Carbon\Carbon::parse($matchday->tanggal)->format('d M Y') }})" />
</x-app-layout>