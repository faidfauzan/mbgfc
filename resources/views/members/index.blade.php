<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Member') }}
        </h2>
    </x-slot>

    <div class="pb-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border border-gray-100">

                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 text-emerald-800 rounded-lg border border-emerald-200 flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 text-rose-800 rounded-lg border border-rose-200">
                        <ul class="list-disc list-inside text-sm font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- HEADER & FILTER SECTION -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Member</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Total terdaftar: <span class="font-semibold text-emerald-600">{{ $members->count() }} member</span></p>
                    </div>

                    <!-- AREA FILTER DAN AKSI -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full sm:w-auto">
                            <!-- Input Search -->
                            <div class="relative w-full sm:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" id="customSearchInput" placeholder="Cari nama atau email..."
                                    class="h-10 w-full pl-9 pr-3 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            </div>

                            <!-- Dropdown Filter Jenis Member -->
                            <div class="relative w-full sm:w-auto">
                                <select id="customFilterJenis"
                                    class="h-10 w-full sm:w-40 pl-3 pr-8 bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                                    <option value="all">Semua Jenis</option>
                                    <option value="prioritas">Prioritas</option>
                                    <option value="umum">Umum</option>
                                </select>
                            </div>

                            <!-- Tombol Reset Filter -->
                            <button type="button" id="customResetBtn"
                                class="hidden h-10 px-3.5 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-700 font-medium rounded-lg transition-all text-sm items-center justify-center gap-1.5 whitespace-nowrap border border-gray-200">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>Reset</span>
                            </button>
                        </div>

                        <!-- TOMBOL TAMBAH MEMBER -->
                        <a href="{{ route('members.create') }}"
                            class="h-10 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold px-4 rounded-lg shadow-sm hover:shadow transition-all whitespace-nowrap text-sm flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Member</span>
                        </a>
                    </div>
                </div>

                <!-- TABLE MEMBER -->
                <div class="w-full overflow-x-auto rounded-xl border border-gray-200">
                    <table id="membersTable" class="w-full min-w-max text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200">
                                <th class="py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                                <th class="py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Handphone</th>
                                <th class="py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                                <th class="py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Pembayaran Prioritas</th>
                                <th class="py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm bg-white">
                            @foreach ($members as $member)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $member->user->name ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-gray-600">
                                        {{ $member->user->email ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-gray-700">{{ $member->no_hp ?? '-' }}</td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        {{-- Menggunakan logic isPrioritasActive() dari model --}}
                                        @if ($member->isPrioritasActive())
                                            <div class="flex flex-col gap-0.5">
                                                <span class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                                    Prioritas
                                                </span>
                                                @if($member->tanggal_berakhir_prioritas)
                                                    <span class="text-[10px] text-gray-500">
                                                        s/d {{ \Carbon\Carbon::parse($member->tanggal_berakhir_prioritas)->format('d M Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                Umum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if ($member->status === 'Nonaktif' ||$member->status_aktif == false)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                Nonaktif
                                            </span>
                                        @elseif ($member->user?->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if ($member->bukti_pembayaran_prioritas)
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 text-[11px] rounded font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    QRIS
                                                </span>
                                                <a href="{{ asset('storage/' . $member->bukti_pembayaran_prioritas) }}" target="_blank"
                                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-medium hover:underline inline-flex items-center gap-1">
                                                    <span>Lihat Bukti</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @elseif ($member->isPrioritasActive())
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-gray-100 text-gray-700 border border-gray-200">
                                                Cash / Admin
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('members.show', $member->id) }}"
                                                class="px-2.5 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-md transition-colors">
                                                Detail
                                            </a>

                                            <a href="{{ route('members.edit', $member) }}"
                                                class="px-2.5 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-md transition-colors">
                                                Edit
                                            </a>

                                            <button type="button"
                                                onclick="openPrioritasModal({{ $member->id }}, '{{ addslashes($member->user->name ?? 'Member') }}', '{{ $member->tanggal_berakhir_prioritas ? \Carbon\Carbon::parse($member->tanggal_berakhir_prioritas)->format('Y-m-d') : '' }}', '{{$member->paket_prioritas ?? '' }}')"
                                                class="px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-md transition-colors">
                                                Atur Prioritas
                                            </button>

                                            @if ($member->isPrioritasActive())
                                                <form action="{{ route('members.cancel-prioritas', $member->id) }}" method="POST" class="inline-block"
                                                    onsubmit="return confirm('Yakin ingin membatalkan status Prioritas member ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-2.5 py-1 text-xs font-medium text-orange-700 bg-orange-50 hover:bg-orange-100 rounded-md transition-colors">
                                                        Batal Prioritas
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-2.5 py-1 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-md transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL EDIT PRIORITAS MEMBER -->
    <div id="prioritasModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 border border-gray-100 transform transition-all">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                <h3 class="text-lg font-bold text-gray-800">Atur Status Prioritas</h3>
                <button type="button" onclick="closePrioritasModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="prioritasForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Member</label>
                    <input type="text" id="modalMemberName" disabled class="w-full bg-gray-100 border border-gray-200 rounded-lg text-sm px-3 py-2 text-gray-700 font-medium cursor-not-allowed">
                </div>

                <div class="mb-4">
                    <label for="paket_prioritas" class="block text-xs font-semibold text-gray-600 mb-1">Paket Prioritas</label>
                    <input type="text" name="paket_prioritas" id="modalPaketPrioritas" placeholder="Contoh: Paket 1 Bulan"
                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 text-gray-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>

                <div class="mb-6">
                    <label for="tanggal_berakhir_prioritas" class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Berakhir Prioritas <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_berakhir_prioritas" id="modalTanggalBerakhir" required
                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 text-gray-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closePrioritasModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Load komponen DataTables -->
    <x-datatables id="membersTable" />

    <script>
        $(document).ready(function () {
            var table = $('#membersTable').DataTable();

            const searchInput = $('#customSearchInput');
            const filterJenis = $('#customFilterJenis');
            const resetBtn = $('#customResetBtn');

            function checkResetBtn() {
                if (searchInput.val().trim() !== '' || filterJenis.val() !== 'all') {
                    resetBtn.removeClass('hidden').addClass('flex');
                } else {
                    resetBtn.addClass('hidden').removeClass('flex');
                }
            }

            searchInput.on('keyup search', function () {
                table.search(this.value).draw();
                checkResetBtn();
            });

            filterJenis.on('change', function () {
                var val = $(this).val();
                if (val === 'all') {
                    table.column(3).search('').draw();
                } else if (val === 'prioritas') {
                    table.column(3).search('Prioritas', true, false).draw();
                } else if (val === 'umum') {
                    table.column(3).search('Umum', true, false).draw();
                }
                checkResetBtn();
            });

            resetBtn.on('click', function () {
                searchInput.val('');
                filterJenis.val('all');
                table.search('').column(3).search('').draw();
                checkResetBtn();
            });
        });

        function openPrioritasModal(id, name, tanggal, paket) {
            const modal = document.getElementById('prioritasModal');
            const form = document.getElementById('prioritasForm');
            
            form.action = `/members/${id}/update-prioritas`;
            document.getElementById('modalMemberName').value = name;
            document.getElementById('modalTanggalBerakhir').value = tanggal;
            document.getElementById('modalPaketPrioritas').value = paket;

            modal.classList.remove('hidden');
        }

        function closePrioritasModal() {
            document.getElementById('prioritasModal').classList.add('hidden');
        }
    </script>
</x-app-layout>