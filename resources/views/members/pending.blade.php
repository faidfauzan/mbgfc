<x-app-layout>
    <div class="pb-6">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Flash Notification Sukses --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Flash Notification Error / Penolakan --}}
            @if(session('error'))
                <div class="p-4 bg-rose-100 text-rose-800 rounded-lg border border-rose-300">
                    {{ session('error') }}
                </div>
            @endif

            {{-- HEADER CARD --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Persetujuan Member Baru</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Member yang mendaftar harus disetujui sebelum bisa mengakses jadwal matchday.
                    </p>
                </div>
                <div>
                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-300 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $pendingMembers->count() }} Menunggu
                    </span>
                </div>
            </div>

            {{-- TABLE CARD --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table id="tabel_auto_2" class="w-full min-w-max text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs uppercase font-semibold text-gray-600">
                                <th class="px-6 py-4 whitespace-nowrap">Member</th>
                                <th class="px-6 py-4 whitespace-nowrap">Email</th>
                                <th class="px-6 py-4 whitespace-nowrap">No. HP</th>
                                <th class="px-6 py-4 whitespace-nowrap">Tanggal Daftar</th>
                                <th class="px-6 py-4 whitespace-nowrap text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @foreach ($pendingMembers as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-semibold text-gray-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-sm">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <span>{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-gray-600">{{ $user->email }}</td>
                                    <td class="p-3 text-gray-700">{{ $user->phone ?? '-' }}</td>
                                    <td class="p-3 text-gray-500 text-xs">
                                        {{ $user->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            {{-- Tombol Setuju --}}
                                            <form action="{{ route('members.approve', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-md transition shadow-sm flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                    Accept
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('members.reject', $user->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran akun {{ $user->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs rounded-md border border-rose-200 transition flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="18" y1="6" x2="6" y2="18" />
                                                        <line x1="6" y1="6" x2="18" y2="18" />
                                                    </svg>
                                                    Reject
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
    <x-datatables id="tabel_auto_2" />
</x-app-layout>