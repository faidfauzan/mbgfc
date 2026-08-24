<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Matchday') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- PERUBAHAN 1: Tambahkan enctype="multipart/form-data" --}}
                <form action="{{ route('matchdays.update', $matchday) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Matchday</label>
                        <input type="text" name="nomor_matchday" value="{{ old('nomor_matchday', $matchday->nomor_matchday) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                        @error('nomor_matchday') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama / Judul Matchday</label>
                        <input type="text" name="nama_matchday" value="{{ old('nama_matchday', $matchday->nama_matchday) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                        @error('nama_matchday') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- PERUBAHAN 2: Input Poster Matchday & Preview Gambar Lama --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Poster Matchday</label>
                        @if($matchday->poster)
                            <div class="mb-2">
                                <span class="block text-xs text-gray-500 mb-1">Poster Saat Ini:</span>
                                <img src="{{ asset('storage/' . $matchday->poster) }}" alt="Poster Matchday" class="w-32 h-44 object-cover rounded-lg border border-gray-300 shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="poster" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 border border-gray-300 rounded-md cursor-pointer">
                        <p class="text-gray-500 text-xs mt-1">Upload gambar baru jika ingin mengganti poster lama (Maks 2MB).</p>
                        @error('poster') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tanggal & Jam Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $matchday->tanggal->format('Y-m-d')) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('tanggal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', $matchday->jam_mulai) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('jam_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', $matchday->jam_selesai) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('jam_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Durasi</label>
                            <input type="text" id="durasi_display" class="w-full text-gray-900 bg-gray-100 border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm text-sm" readonly placeholder="Otomatis">
                            <input type="hidden" name="durasi_menit" id="durasi_menit" value="{{ old('durasi_menit', $matchday->durasi_menit) }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi / Lapangan</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $matchday->lokasi) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                        @error('lokasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">HTM (Rupiah)</label>
                            <input type="number" name="htm" value="{{ old('htm', $matchday->htm) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('htm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kuota Peserta</label>
                            <input type="number" name="kuota" value="{{ old('kuota', $matchday->kuota) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('kuota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Section Fasilitas --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fasilitas yang Didapat</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-3 rounded-md border border-gray-200">
                            @php
                                $options = ['Jersey', 'Rompi', 'Air Minum', 'Snack', 'Fotografer', 'Videografer'];
                                $selectedFasilitas = old('fasilitas', $matchday->fasilitas ?? []);
                            @endphp
                            @foreach($options as $item)
                                <label class="inline-flex items-center text-sm text-gray-700 cursor-pointer">
                                    <input type="checkbox" name="fasilitas[]" value="{{ $item }}" 
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ in_array($item, $selectedFasilitas) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $item }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Catatan (Opsional)</label>
                        <textarea name="catatan" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" rows="3">{{ old('catatan', $matchday->catatan) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status Pendaftaran</label>
                        <select name="status" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm">
                            <option value="open" {{ old('status', $matchday->status) == 'open' ? 'selected' : '' }}>Open (Buka Pendaftaran)</option>
                            <option value="closed" {{ old('status', $matchday->status) == 'closed' ? 'selected' : '' }}>Closed (Tutup Pendaftaran)</option>
                            <option value="finished" {{ old('status', $matchday->status) == 'finished' ? 'selected' : '' }}>Finished (Selesai)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a href="{{ route('matchdays.index') }}" 
                           class="bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition">
                            Batal
                        </a>

                        <button type="submit" 
                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition">
                            Update
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Script Hitung Durasi Otomatis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jamMulaiInput = document.getElementById('jam_mulai');
            const jamSelesaiInput = document.getElementById('jam_selesai');
            const durasiDisplay = document.getElementById('durasi_display');
            const durasiMenitInput = document.getElementById('durasi_menit');

            function hitungDurasi() {
                if (jamMulaiInput.value && jamSelesaiInput.value) {
                    let [h1, m1] = jamMulaiInput.value.split(':').map(Number);
                    let [h2, m2] = jamSelesaiInput.value.split(':').map(Number);
                    
                    let start = h1 * 60 + m1;
                    let end = h2 * 60 + m2;
                    
                    if (end > start) {
                        let diff = end - start;
                        durasiMenitInput.value = diff;
                        
                        let jam = Math.floor(diff / 60);
                        let menit = diff % 60;
                        let text = '';
                        if (jam > 0) text += `${jam} Jam `;
                        if (menit > 0) text += `${menit} Mnt`;
                        
                        durasiDisplay.value = text.trim();
                    } else {
                        durasiDisplay.value = 'Tidak Valid';
                        durasiMenitInput.value = '';
                    }
                }
            }

            jamMulaiInput.addEventListener('change', hitungDurasi);
            jamSelesaiInput.addEventListener('change', hitungDurasi);
            hitungDurasi();
        });
    </script>
</x-app-layout>