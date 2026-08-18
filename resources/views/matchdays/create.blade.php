<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Matchday Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('matchdays.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Matchday</label>
                        <input type="text" name="nomor_matchday" value="{{ old('nomor_matchday') }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" placeholder="MD-01" required>
                        @error('nomor_matchday') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama / Judul Matchday</label>
                        <input type="text" name="nama_matchday" value="{{ old('nama_matchday') }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" placeholder="Friendly Match" required>
                        @error('nama_matchday') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tanggal & Jam Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('tanggal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('jam_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('jam_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Durasi</label>
                            <input type="text" id="durasi_display" class="w-full text-gray-900 bg-gray-100 border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm text-sm" readonly placeholder="Otomatis">
                            <input type="hidden" name="durasi_menit" id="durasi_menit" value="{{ old('durasi_menit') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi / Lapangan</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" placeholder="Lapangan Futsal A" required>
                        @error('lokasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">HTM (Rupiah)</label>
                            <input type="number" name="htm" value="{{ old('htm', 0) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('htm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kuota Peserta</label>
                            <input type="number" name="kuota" value="{{ old('kuota', 15) }}" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" required>
                            @error('kuota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Section Fasilitas --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fasilitas yang Didapat</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-3 rounded-md border border-gray-200">
                            @php
                                $options = ['Jersey', 'Rompi', 'Air Minum', 'Snack', 'Fotografer', 'Videografer'];
                                $selectedFasilitas = old('fasilitas', []);
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
                        <textarea name="catatan" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm" rows="3">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status Pendaftaran</label>
                        <select name="status" class="w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm">
                            <option value="open">Open (Buka Pendaftaran)</option>
                            <option value="closed">Closed (Tutup Pendaftaran)</option>
                            <option value="finished">Finished (Selesai)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
    <!-- TOMBOL BATAL (Merah) -->
    <a href="{{ route('matchdays.index') }}" 
       class="bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition">
        Batal
    </a>

    <!-- TOMBOL SIMPAN (Hijau) -->
    <button type="submit" 
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition">
        Simpan
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