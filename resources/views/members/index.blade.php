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
                    
                    <!-- TOMBOL TAMBAH MEMBER (Hijau Emerald MBG FC) -->
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
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">No. Punggung</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">Posisi</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">Jenis</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap">Status</th>
                                <th class="p-3 text-sm font-semibold text-gray-700 whitespace-nowrap text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($members as $member)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 whitespace-nowrap font-medium text-gray-900">{{ $member->user->name ?? '-' }}</td>
                                    <td class="p-3 whitespace-nowrap text-gray-600">{{ $member->user->email ?? '-' }}</td>
                                    <td class="p-3 whitespace-nowrap text-gray-700">{{ $member->nomor_punggung ?? '-' }}</td>
                                    <td class="p-3 whitespace-nowrap text-gray-700">{{ $member->posisi ?? '-' }}</td>
                                    <td class="p-3 whitespace-nowrap capitalize text-gray-700">{{ $member->jenis_member }}</td>
                                    <td class="p-3 whitespace-nowrap">
                                        <!-- BADGE STATUS AKTIF / NONAKTIF -->
                                        @if ($member->status_aktif)
                                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold bg-rose-100 text-rose-800 border border-rose-300">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('members.edit', $member) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs bg-indigo-50 px-3 py-1.5 rounded-md">Edit</a>
                                            <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs bg-red-50 px-3 py-1.5 rounded-md">Delete</button>
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