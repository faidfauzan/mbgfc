<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    // Menampilkan daftar semua member (kecuali Admin/Captain)
   // Menampilkan daftar semua member (kecuali Admin/Captain) + Fitur Search & Filter
    public function index(Request $request)
    {
        $query = Member::whereHas('user', function ($q) {
            $q->whereNotIn('role', ['captain', 'admin']);
        })->with('user');

        // 1. Fitur Search (Berdasarkan Nama Member atau Email User)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Fitur Filter Jenis Member (Umum/Reguler atau Prioritas)
        if ($request->filled('jenis') && $request->jenis !== 'all') {
            if ($request->jenis === 'prioritas') {
                $query->where('jenis_member', 'prioritas');
            } elseif ($request->jenis === 'umum') {
                $query->where('jenis_member', 'umum');
            }
        }

        // Simpan query parameter saat melakukan pagination
        $members = $query->latest()->paginate(15)->withQueryString();

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
            'email' => 'required|email|unique:users,email',
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
            'name' => $validated['name'],
            'nomor_punggung' => $validated['nomor_punggung'] ?? null,
            'posisi' => $validated['posisi'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? now(),
            'jenis_member' => $validated['jenis_member'],
            'status_aktif' => true,
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
            'email' => 'required|email|unique:users,email,' . $member->user_id,
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

        if ($member->user) {
            $member->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }

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
        // 1. Cegah Admin/Superadmin menghapus akunnya sendiri
        if ($member->user_id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akun kamu sendiri yang sedang login!');
        }

        // 2. Hapus data member beserta relasi user-nya
        $user = $member->user;
        $member->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()->route('members.index')->with('success', 'Member berhasil dihapus.');
    }

    public function show(Member $member)
    {
        // Ubah 'registrations.matchday' menjadi 'matchdayRegistrations.matchday'
        $member->load(['user', 'matchdayRegistrations.matchday']);

        return view('history.member-detail', compact('member'));
    }

    // Menampilkan daftar member yang statusnya masih 'pending'
    public function pendingList()
    {
        $pendingMembers = \App\Models\User::where('status', 'pending')
            ->where('role', 'member')
            ->latest()
            ->get();

        return view('members.pending', compact('pendingMembers'));
    }

    // Menyetujui (ACC) akun member
    public function approve($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', 'Akun ' . $user->name . ' berhasil di-ACC!');
    }

    // Menolak & menghapus akun member pending
    public function reject($id)
    {
        $user = \App\Models\User::findOrFail($id);
        // Hapus data member jika sudah terlanjur dibuat relasinya
        if ($user->member) {
            $user->member->delete();
        }
        $user->delete();
        return back()->with('error', 'Pendaftaran akun ' . $user->name . ' telah ditolak dan dihapus.');
    }
}