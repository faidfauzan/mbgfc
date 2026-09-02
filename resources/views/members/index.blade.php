<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Daftar Member ({{ $members->total() }})</h3>

                    <!-- TOMBOL TAMBAH MEMBER -->
                    <a href="{{ route('members.create') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition whitespace-nowrap text-sm">
                        + Tambah Member
                    </a>
                </div>

                <!-- TABLE MEMBER -->
                <div class="w-full overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full min-w-max text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">Nama</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">Email</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">No. Handphone</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">Jenis</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">Status</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">Pembayaran Prioritas</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap text-center">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($members as $member)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 whitespace-nowrap font-medium text-gray-900">
                                        {{ $member->user->name ?? '-' }}
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-gray-600">{{ $member->user->email ?? '-' }}</td>
                                    <td class="p-3 whitespace-nowrap text-gray-700">{{ $member->no_hp ?? '-' }}</td>
                                    <td class="p-3 whitespace-nowrap">
                                        @if ($member->isPrioritasActive())
                                            <span
                                                class="px-2.5 py-1 text-xs rounded-full font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                                                Prioritas
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs rounded-full font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                Umum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 whitespace-nowrap">
                                        @if ($member->status_aktif)
                                            <span
                                                class="px-2.5 py-1 text-xs rounded-full font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs rounded-full font-semibold bg-rose-100 text-rose-800 border border-rose-300">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <!-- KOLOM BUKTI PEMBAYARAN -->
                                    <td class="p-3 whitespace-nowrap">
                                        @if ($member->bukti_pembayaran_prioritas)
                                            {{-- Jika ada bukti upload = Pembayaran via QRIS --}}
                                            <div class="flex flex-col items-start gap-0.5">
                                                <span
                                                    class="px-2 py-0.5 text-[11px] rounded font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                                    QRIS
                                                </span>
                                                <a href="{{ asset('storage/' . $member->bukti_pembayaran_prioritas) }}"
                                                    target="_blank"
                                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-medium underline">
                                                    Lihat Bukti
                                                </a>
                                            </div>
                                        @elseif ($member->isPrioritasActive())
                                            {{-- Jika Prioritas Aktif tapi TANPA bukti upload = Pembayaran Cash --}}
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 border border-slate-300">
                                                Cash
                                            </span>
                                        @else
                                            {{-- Member Umum / Belum Bayar --}}
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('members.edit', $member) }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs bg-indigo-50 px-3 py-1.5 rounded-md">Edit</a>
                                            <form action="{{ route('members.destroy', $member) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 font-semibold text-xs bg-red-50 px-3 py-1.5 rounded-md">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center text-gray-500">Belum ada data member.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $members->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<!-- habis tak hapus -->