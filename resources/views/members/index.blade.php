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
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Member ({{ $members->total() }})</h3>
                    <a href="{{ route('members.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 whitespace-nowrap">
                        + Tambah Member
                    </a>
                </div>

                <!-- PEMBUNGKUS TABEL DENGAN SCROLL HORIZONTAL -->
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
                                        @if ($member->status_aktif)
                                            <span class="px-2 py-1 text-xs rounded-full font-semibold bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full font-semibold bg-red-100 text-red-800">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-center">
                                        <a href="{{ route('members.edit', $member) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs bg-indigo-50 px-3 py-1.5 rounded-md">Edit</a>
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