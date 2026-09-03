<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Kelola Pengumuman</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 font-medium text-sm rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Daftar Pengumuman</h3>
                    <a href="{{ route('announcements.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-4 py-2 rounded-lg transition shadow-sm">
                        + Buat Pengumuman
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-100">
                                <th class="p-3 text-xs font-bold text-slate-900 uppercase tracking-wider">Judul</th>
                                <th class="p-3 text-xs font-bold text-slate-900 uppercase tracking-wider">Dibuat Oleh</th>
                                <th class="p-3 text-xs font-bold text-slate-900 uppercase tracking-wider">Waktu</th>
                                <th class="p-3 text-xs font-bold text-slate-900 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white text-sm">
                            @forelse ($announcements as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3 font-semibold text-slate-900">{{ $item->judul }}</td>
                                    <td class="p-3 font-medium text-slate-800">{{ $item->creator->name ?? ($item->user->name ?? 'System') }}</td>
                                    <td class="p-3 font-medium text-slate-700 whitespace-nowrap">{{ $item->created_at->diffForHumans() }}</td>
                                    <td class="p-3 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('announcements.edit', $item) }}" class="text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-md text-xs font-bold transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('announcements.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-1 rounded-md text-xs font-bold transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center font-medium text-slate-600">Belum ada pengumuman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>