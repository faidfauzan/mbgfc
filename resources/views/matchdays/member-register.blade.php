<x-app-layout>
    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-lg text-white">
            <h2 class="text-xl font-bold mb-1">Form Pendaftaran Matchday</h2>
            <p class="text-sm text-gray-400 mb-6">{{ $matchday->nama_matchday ?? $matchday->nama }} — HTM: Rp
                {{ number_format($matchday->htm, 0, ',', '.') }}
            </p>

            <form action="{{ route('matchday.member.store', $matchday) }}" method="POST" enctype="multipart/form-data"
                class="space-y-5" x-data="{ metode: 'qris' }">
                @csrf

                <!-- Pilih Posisi Bermain -->
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-200">Posisi Bermain</label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Input Pemain -->
                        <label for="posisi_player"
                            class="flex items-center gap-3 p-3 bg-slate-800 rounded-lg border border-slate-700 cursor-pointer hover:border-emerald-500 transition">
                            <input type="radio" id="posisi_player" name="posisi" value="non_kiper" checked
                                class="text-emerald-500 focus:ring-emerald-500">
                            <span class="text-sm font-medium">Pemain (Non-Kiper)</span>
                        </label>

                        <!-- Input Kiper -->
                        <label for="posisi_gk"
                            class="flex items-center gap-3 p-3 bg-slate-800 rounded-lg border border-slate-700 cursor-pointer hover:border-emerald-500 transition">
                            <input type="radio" id="posisi_gk" name="posisi" value="kiper"
                                class="text-emerald-500 focus:ring-emerald-500">
                            <span class="text-sm font-medium">Kiper (GK)</span>
                        </label>
                    </div>
                </div>

                <!-- Opsi Member Prioritas -->
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-200">Jenis Pendaftaran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="flex items-center gap-3 p-3 bg-slate-800 rounded-lg border border-slate-700 cursor-pointer hover:border-emerald-500 transition">
                            <input type="radio" name="is_prioritas" value="0" checked
                                class="text-emerald-500 focus:ring-emerald-500">
                            <div>
                                <p class="text-sm font-medium">Reguler</p>
                                <p class="text-xs text-gray-400">Pendaftaran biasa</p>
                            </div>
                        </label>
                        <label
                            class="flex items-center gap-3 p-3 bg-slate-800 rounded-lg border border-slate-700 cursor-pointer hover:border-emerald-500 transition">
                            <input type="radio" name="is_prioritas" value="1"
                                class="text-emerald-500 focus:ring-emerald-500">
                            <div>
                                <p class="text-sm font-medium text-amber-400">Member Prioritas</p>
                                <p class="text-xs text-gray-400">Slot diprioritaskan</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-200">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="flex items-center gap-3 p-3 bg-slate-800 rounded-lg border border-slate-700 cursor-pointer hover:border-emerald-500 transition">
                            <input type="radio" name="metode_pembayaran" value="qris" x-model="metode"
                                class="text-emerald-500 focus:ring-emerald-500">
                            <span class="text-sm font-medium">QRIS / Transfer</span>
                        </label>
                        <label
                            class="flex items-center gap-3 p-3 bg-slate-800 rounded-lg border border-slate-700 cursor-pointer hover:border-emerald-500 transition">
                            <input type="radio" name="metode_pembayaran" value="cash" x-model="metode"
                                class="text-emerald-500 focus:ring-emerald-500">
                            <span class="text-sm font-medium">Bayar Cash (Bayar di Lapangan)</span>
                        </label>
                    </div>
                </div>

                <!-- Area QRIS & Upload -->
                <div x-show="metode === 'qris'" x-data="{ isZoomed: false }"
                    class="p-4 bg-slate-800/60 rounded-xl border border-slate-700 space-y-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-300 mb-1">Scan QRIS di bawah ini untuk pembayaran:</p>
                        <p class="text-[11px] text-emerald-400 mb-3 italic cursor-pointer"
                            @click="isZoomed = !isZoomed">
                            <span
                                x-text="isZoomed ? '🔍 Klik lagi untuk mengecilkan' : '🔍 Klik gambar untuk memperbesar'"></span>
                        </p>

                        <!-- Gambar QRIS (Toggle Ukuran) -->
                        <div class="inline-block cursor-pointer transition-all duration-300 w-full"
                            @click="isZoomed = !isZoomed">
                            <img src="{{ asset('images/QRIScode.jpeg') }}" alt="QRIS Code"
                                :class="isZoomed ? 'w-full max-w-md' : 'w-44'"
                                class="mx-auto rounded-lg border-2 border-slate-600 bg-white p-2 shadow-md hover:border-emerald-500 transition-all duration-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-200">Upload Bukti Pembayaran
                            (Wajib)</label>
                        <input type="file" name="bukti_bayar" :required="metode === 'qris'"
                            class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 cursor-pointer">
                        @error('bukti_bayar')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex justify-between items-center pt-4 border-t border-slate-800">
                    <a href="{{ route('matchday.member.show', $matchday) }}"
                        class="text-sm text-gray-400 hover:text-white">Batal</a>
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-6 py-2.5 rounded-lg transition shadow-md">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>