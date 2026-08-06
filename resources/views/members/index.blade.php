<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Data Member
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
                    <a href="{{ route('members.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        + Tambah Member
                    </a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-2">Nama</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">No. Punggung</th>
                            <th class="p-2">Posisi</th>
                            <th class="p-2">Jenis</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($members as $member)
                            <tr class="border-b">
                                <td class="p-2">{{ $member->user->name }}</td>
                                <td class="p-2">{{ $member->user->email }}</td>
                                <td class="p-2">{{ $member->nomor_punggung ?? '-' }}</td>
                                <td class="p-2">{{ $member->posisi ?? '-' }}</td>
                                <td class="p-2 capitalize">{{ $member->jenis_member }}</td>
                                <td class="p-2">
                                    @if ($member->status_aktif)
                                        <span class="text-green-600">Aktif</span>
                                    @else
                                        <span class="text-red-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="p-2">
                                    <a href="{{ route('members.edit', $member) }}" class="text-indigo-600 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-4 text-center text-gray-500">Belum ada data member.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $members->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>