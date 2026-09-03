<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">Edit Pengumuman</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ route('announcements.update', $announcement) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-bold text-black">Judul Pengumuman</label>
                        <input type="text" name="judul" value="{{ old('judul', $announcement->judul) }}" required class="mt-1 block w-full rounded-md border-gray-300 text-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('judul') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-black">Isi Pengumuman</label>
                        <textarea name="isi" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 text-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('isi', $announcement->isi) }}</textarea>
                        @error('isi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('announcements.index') }}" class="px-4 py-2 bg-gray-200 text-black font-medium rounded-lg hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>