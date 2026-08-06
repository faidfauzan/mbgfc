<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Member
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('members.update', $member) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $member->user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $member->user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nomor Punggung</label>
                        <input type="number" name="nomor_punggung" value="{{ old('nomor_punggung', $member->nomor_punggung) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('nomor_punggung') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Posisi Bermain</label>
                        <input type="text" name="posisi" value="{{ old('posisi', $member->posisi) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('posisi') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nomor HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $member->no_hp) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('no_hp') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Tanggal Bergabung</label>
                        <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $member->tanggal_bergabung?->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('tanggal_bergabung') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Jenis Member</label>
                        <select name="jenis_member" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="umum" {{ old('jenis_member', $member->jenis_member) == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="prioritas" {{ old('jenis_member', $member->jenis_member) == 'prioritas' ? 'selected' : '' }}>Prioritas</option>
                        </select>
                        @error('jenis_member') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4" id="field-prioritas">
    <label class="block font-medium text-sm text-gray-700">Paket Prioritas</label>
    <select name="paket_prioritas" class="mt-1 block w-full border-gray-300 rounded-md">
        <option value="">- Pilih Paket -</option>
        <option value="Bulanan" {{ old('paket_prioritas', $member->paket_prioritas) == 'Bulanan' ? 'selected' : '' }}>Bulanan (Rp15.000)</option>
        <option value="2 Bulan" {{ old('paket_prioritas', $member->paket_prioritas) == '2 Bulan' ? 'selected' : '' }}>2 Bulan (Rp25.000)</option>
        <option value="6 Bulan" {{ old('paket_prioritas', $member->paket_prioritas) == '6 Bulan' ? 'selected' : '' }}>6 Bulan (Rp50.000)</option>
        <option value="Tahunan" {{ old('paket_prioritas', $member->paket_prioritas) == 'Tahunan' ? 'selected' : '' }}>Tahunan (Rp75.000)</option>
    </select>
    @error('paket_prioritas') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-6" id="field-tanggal-berakhir">
    <label class="block font-medium text-sm text-gray-700">Tanggal Berakhir Prioritas</label>
    <input type="date" name="tanggal_berakhir_prioritas" value="{{ old('tanggal_berakhir_prioritas', $member->tanggal_berakhir_prioritas?->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md">
    @error('tanggal_berakhir_prioritas') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

                    <div class="mb-6 flex items-center gap-2">
                        <input type="checkbox" name="status_aktif" id="status_aktif" value="1" {{ old('status_aktif', $member->status_aktif) ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="status_aktif" class="text-sm text-gray-700">Member aktif</label>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('members.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                            Batal
                        </a>
                    </div>>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>