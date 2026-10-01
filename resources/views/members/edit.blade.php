<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Member
        </h2>
    </x-slot>

    <div class="pb-6">
        <div class="max-w-3xl mx-auto ">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('members.update', $member) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $member->user->name) }}"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                        @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $member->user->email) }}"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                        @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nomor Punggung</label>
                        <input type="number" name="nomor_punggung"
                            value="{{ old('nomor_punggung', $member->nomor_punggung) }}"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                        @error('nomor_punggung')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Posisi Bermain</label>
                        <input type="text" name="posisi" value="{{ old('posisi', $member->posisi) }}"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                        @error('posisi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nomor HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $member->no_hp) }}"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                        @error('no_hp')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Tanggal Bergabung</label>
                        <input type="date" name="tanggal_bergabung"
                            value="{{ old('tanggal_bergabung', $member->tanggal_bergabung?->format('Y-m-d')) }}"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                        @error('tanggal_bergabung')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Jenis Member</label>
                        <select name="jenis_member"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                            <option value="umum" {{ old('jenis_member', $member->jenis_member) == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="prioritas" {{ old('jenis_member', $member->jenis_member) == 'prioritas' ? 'selected' : '' }}>Prioritas</option>
                        </select>
                        @error('jenis_member')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4" id="field-prioritas">
                        <label class="block font-medium text-sm text-gray-700">Paket Prioritas</label>
                        <select name="paket_prioritas"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                            <option value="">- Pilih Paket -</option>
                            <option value="Bulanan" {{ old('paket_prioritas', $member->paket_prioritas) == 'Bulanan' ? 'selected' : '' }}>Bulanan (Rp15.000)</option>
                            <option value="2 Bulan" {{ old('paket_prioritas', $member->paket_prioritas) == '2 Bulan' ? 'selected' : '' }}>2 Bulan (Rp25.000)</option>
                            <option value="6 Bulan" {{ old('paket_prioritas', $member->paket_prioritas) == '6 Bulan' ? 'selected' : '' }}>6 Bulan (Rp50.000)</option>
                            <option value="Tahunan" {{ old('paket_prioritas', $member->paket_prioritas) == 'Tahunan' ? 'selected' : '' }}>Tahunan (Rp75.000)</option>
                        </select>
                        @error('paket_prioritas')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6" id="field-tanggal-berakhir">
                        <label class="block font-medium text-sm text-gray-700">Tanggal Berakhir Prioritas</label>
                        <input type="date" name="tanggal_berakhir_prioritas"
                            value="{{ old('tanggal_berakhir_prioritas', $member->tanggal_berakhir_prioritas?->format('Y-m-d')) }}"
                            class="mt-1 block w-full text-gray-900 bg-white border-gray-300 focus:text-gray-900 placeholder-gray-400 rounded-md">
                        @error('tanggal_berakhir_prioritas')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- tombol non aktif -->
                    <!-- tombol status member -->
                    <div x-data="{ isDisabled: {{ ($member->status === 'Nonaktif' || !$member->status_aktif) ? 'true' : 'false' }} }"
                        class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            Status Akun Member
                        </label>

                        <!-- Terikat nilai 1 untuk Nonaktif, 0 untuk Aktif -->
                        <input type="hidden" name="is_disabled" :value="isDisabled ? '1' : '0'">

                        <button type="button" @click="isDisabled = !isDisabled"
                            class="flex items-center justify-between w-full sm:w-80 px-4 py-3 rounded-xl border transition-all duration-200"
                            :class="isDisabled 
            ? 'bg-rose-50 dark:bg-rose-500/10 border-rose-200 dark:border-rose-500/30' 
            : 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/30'">

                            <div class="flex items-center gap-2.5">
                                <!-- Icon Status Aktif -->
                                <svg x-show="!isDisabled" x-cloak class="w-5 h-5 text-emerald-600 dark:text-emerald-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
                                    <line x1="12" y1="2" x2="12" y2="12" />
                                </svg>

                                <!-- Icon Status Nonaktif -->
                                <svg x-show="isDisabled" x-cloak class="w-5 h-5 text-rose-600 dark:text-rose-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
                                    <line x1="12" y1="2" x2="12" y2="12" />
                                </svg>

                                <span class="text-sm font-semibold"
                                    :class="isDisabled ? 'text-rose-700 dark:text-rose-400' : 'text-emerald-700 dark:text-emerald-400'"
                                    x-text="isDisabled ? 'Member Nonaktif' : 'Member Aktif'">
                                </span>
                            </div>

                            <!-- Toggle switch (Posisi kanan / ON = Member Aktif [Hijau], Posisi kiri / OFF = Nonaktif [Merah]) -->
                            <div class="relative w-11 h-6 rounded-full transition-colors duration-200"
                                :class="isDisabled ? 'bg-rose-500' : 'bg-emerald-500'">
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"
                                    :class="isDisabled ? 'translate-x-0' : 'translate-x-5'">
                                </div>
                            </div>
                        </button>

                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-2" x-show="isDisabled" x-cloak>
                            Member akan kehilangan akses ke jadwal matchday dan harus menunggu diaktifkan kembali oleh
                            admin.
                        </p>
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
                            perbarui
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>