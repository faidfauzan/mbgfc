<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        
        <!-- Header / Back Link -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-white transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-700 p-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-amber-500/20 text-amber-400 rounded-xl">
                        👑
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Upgrade Member Prioritas</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Dapatkan prioritas slot matchday dan dapatkan keuntungan khusus.</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6 sm:p-8">
                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('prioritas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Pilih Paket -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Pilih Paket Membership</label>
                        <select name="paket_prioritas" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all">
                            <option value="" disabled selected>-- Pilih Paket --</option>
                            <option value="1_bulan">Bulanan — Rp 15.000</option>
                            <option value="2_bulan">2 Bulan — Rp 25.000</option>
                            <option value="6_bulan">6 Bulan — Rp 50.000</option>
                            <option value="1_tahun">Tahunan — Rp 75.000</option>
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 bg-slate-800/50 border border-slate-700 rounded-xl cursor-pointer hover:border-slate-600 transition-all">
                                <input type="radio" name="metode_pembayaran" value="qris" checked onclick="toggleQRIS(true)" class="text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900 bg-slate-900 border-slate-700">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-white">QRIS / Transfer</span>
                                    <span class="block text-xs text-slate-400">Bayar online & upload bukti</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 bg-slate-800/50 border border-slate-700 rounded-xl cursor-pointer hover:border-slate-600 transition-all">
                                <input type="radio" name="metode_pembayaran" value="cash" onclick="toggleQRIS(false)" class="text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900 bg-slate-900 border-slate-700">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-white">Bayar Cash</span>
                                    <span class="block text-xs text-slate-400">Bayar langsung di lapangan</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Scan QRIS & Upload Bukti -->
                    <div id="qrisSection" class="p-5 bg-slate-800/30 border border-slate-700/60 rounded-xl space-y-4">
                        <div class="text-center">
                            <p class="text-xs text-slate-400 mb-3">Scan QRIS di bawah ini untuk menyelesaikan pembayaran:</p>
                            <div class="inline-block p-2 bg-white rounded-xl shadow-md">
                                <img src="{{ asset('images/QRISCode.jpeg') }}" alt="QRIS" class="w-48 h-auto rounded-lg">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Upload Bukti Pembayaran</label>
                            <input type="file" id="buktiInput" name="bukti_pembayaran" accept="image/*" required class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-400 hover:file:bg-amber-500/20 file:transition-all cursor-pointer">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-800">
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold rounded-xl text-sm shadow-lg shadow-amber-500/10 transition-all">
                            Daftar Prioritas Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleQRIS(show) {
            const qrisSection = document.getElementById('qrisSection');
            const buktiInput = document.getElementById('buktiInput');
            
            if (show) {
                qrisSection.style.display = 'block';
                buktiInput.required = true;
            } else {
                qrisSection.style.display = 'none';
                buktiInput.required = false;
            }
        }
    </script>
</x-app-layout>