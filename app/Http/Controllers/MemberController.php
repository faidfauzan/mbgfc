<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    // Menampilkan daftar semua member
    public function index()
    {
        $members = Member::with('user')->latest()->paginate(15);
        return view('members.index', compact('members'));
    }

    // Menampilkan form tambah member
    public function create()
    {
        return view('members.create');
    }

    // Menyimpan member baru (sekaligus bikin akun login-nya)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // DIUBAH: Hapus $member->id di sini
            'password' => 'required|string|min:8',
            'nomor_punggung' => 'nullable|integer',
            'posisi' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_bergabung' => 'nullable|date',
            'jenis_member' => 'required|in:umum,prioritas',
            'paket_prioritas' => 'nullable|string',
            'tanggal_berakhir_prioritas' => 'nullable|date',
        ]);

        if ($validated['jenis_member'] === 'prioritas') {
            $jumlahPrioritasAktif = Member::where('jenis_member', 'prioritas')
                ->where('status_aktif', true)
                ->count();

            if ($jumlahPrioritasAktif >= 15) {
                return back()->withErrors(['jenis_member' => 'Kuota Member Prioritas sudah penuh (maksimal 15 orang).'])->withInput();
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
        ]);

        $user->member()->create([
            'nomor_punggung' => $validated['nomor_punggung'] ?? null,
            'posisi' => $validated['posisi'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? null,
            'jenis_member' => $validated['jenis_member'],
            'paket_prioritas' => $validated['paket_prioritas'] ?? null,
            'tanggal_berakhir_prioritas' => $validated['tanggal_berakhir_prioritas'] ?? null,
        ]);

        return redirect()->route('members.index')->with('success', 'Member berhasil ditambahkan.');
    }

    // Menampilkan form edit member
    public function edit(Member $member)
    {
        $member->load('user');
        return view('members.edit', compact('member'));
    }

    // Menyimpan perubahan data member
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->user_id, // BENAR: Memakai $member->user_id untuk pengecualian
            'nomor_punggung' => 'nullable|integer',
            'posisi' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_bergabung' => 'nullable|date',
            'jenis_member' => 'required|in:umum,prioritas',
            'status_aktif' => 'nullable|boolean',
            'paket_prioritas' => 'nullable|string',
            'tanggal_berakhir_prioritas' => 'nullable|date',
        ]);

        if ($validated['jenis_member'] === 'prioritas' && $member->jenis_member !== 'prioritas') {
            $jumlahPrioritasAktif = Member::where('jenis_member', 'prioritas')
                ->where('status_aktif', true)
                ->count();

            if ($jumlahPrioritasAktif >= 15) {
                return back()->withErrors(['jenis_member' => 'Kuota Member Prioritas sudah penuh (maksimal 15 orang).'])->withInput();
            }
        }

        $member->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $member->update([
            'nomor_punggung' => $validated['nomor_punggung'] ?? null,
            'posisi' => $validated['posisi'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? null,
            'jenis_member' => $validated['jenis_member'],
            'status_aktif' => $request->boolean('status_aktif'),
            'paket_prioritas' => $validated['paket_prioritas'] ?? null,
            'tanggal_berakhir_prioritas' => $validated['tanggal_berakhir_prioritas'] ?? null,
        ]);

        return redirect()->route('members.index')->with('success', 'Data member berhasil diperbarui.');
    }

    // Menghapus member
    public function destroy(Member $member)
    {
        $user = $member->user;
        $member->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('members.index')->with('success', 'Member berhasil dihapus.');
    }
}