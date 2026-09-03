<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Semua Pengumuman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($announcements as $item)
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-gray-900">{{ $item->judul }}</h3>
                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                    <!-- Tampilkan seluruh isi tulisan secara utuh tanpa pemotongan -->
                    <p class="text-gray-700 text-sm whitespace-pre-line">{{ $item->isi }}</p>
                </div>
            @empty
                <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                    Belum ada pengumuman yang diterbitkan.
                </div>
            @endforelse

            <div class="mt-4">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>