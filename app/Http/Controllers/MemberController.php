<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    // Menampilkan daftar semua member (kecuali Admin/Captain)
    public function index(Request $request)
    {
        // Mengambil member yang role user-nya BUKAN admin atau captain
        $members = Member::whereHas('user', function ($query) {
            $query->whereNotIn('role', ['admin', 'captain']);
        })
            ->with('user')
            ->latest()
            ->get();

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
            'status' => 'active',
        ]);

        $user->member()->create([
            'name' => $validated['name'],
            'nomor_punggung' => $validated['nomor_punggung'] ?? null,
            'posisi' => $validated['posisi'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? now(),
            'jenis_member' => $validated['jenis_member'],
            'status' => 'Aktif',
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
            'is_disabled' => 'nullable|in:0,1',
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

        // Tentukan nilai status berdasarkan input toggle (is_disabled = 1 artinya Nonaktif)
        $isDisabled = $request->input('is_disabled') == '1';
        $statusString = $isDisabled ? 'Nonaktif' : 'Aktif';
        $statusBool = !$isDisabled;

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
            'status' => $statusString,         // Mengisi kolom status (Aktif / Nonaktif)
            'status_aktif' => $statusBool,      // Mengisi kolom boolean status_aktif (true / false)
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
        if ($user->member) {
            $user->member->delete();
        }
        $user->delete();
        return back()->with('error', 'Pendaftaran akun ' . $user->name . ' telah ditolak dan dihapus.');
    }

    /**
     * Mengedit tanggal & paket prioritas member secara manual oleh Admin.
     */
    public function updatePrioritas(Request $request, $id)
    {
        $request->validate([
            'tanggal_berakhir_prioritas' => 'required|date',
            'paket_prioritas' => 'nullable|string',
        ], [
            'tanggal_berakhir_prioritas.required' => 'Tanggal berakhir prioritas wajib diisi.',
            'tanggal_berakhir_prioritas.date' => 'Format tanggal tidak valid.',
        ]);

        $member = Member::findOrFail($id);
        
        $member->update([
            'jenis_member' => 'prioritas',
            'paket_prioritas' => $request->paket_prioritas ?? $member->paket_prioritas,
            'tanggal_berakhir_prioritas' => $request->tanggal_berakhir_prioritas,
        ]);

        return redirect()->back()->with('success', 'Paket prioritas member berhasil diperbarui.');
    }

    /**
     * Membatalkan status prioritas member (Kembali menjadi member Umum).
     */
    public function cancelPrioritas($id)
    {
        DB::transaction(function () use ($id) {
            $member = Member::findOrFail($id);

            // 1. Reset status member menjadi 'umum'
            $member->update([
                'jenis_member' => 'umum',
                'paket_prioritas' => null,
                'tanggal_berakhir_prioritas' => null,
                'bukti_pembayaran_prioritas' => null,
            ]);

            // 2. Ambil semua pendaftaran matchday mendatang milik member ini
            $registrations = $member->matchdayRegistrations()
                ->whereHas('matchday', function ($query) {
                    $query->where('tanggal', '>=', now()->toDateString());
                })
                ->get();

            foreach ($registrations as $reg) {
                $matchday = $reg->matchday;
                if (!$matchday) continue;

                // Hanya proses jika posisi pendaftaran member ini saat ini adalah 'utama' / 'approved'
                if (in_array(strtolower($reg->status), ['utama', 'approved'])) {

                    // A. Turunkan status member ex-prioritas ini ke Waiting List
                    $reg->update([
                        'status' => 'waiting_list',
                    ]);

                    // B. Cari member reguler lain di matchday & posisi yang sama yang sedang di Waiting List (pendaftar terawal)
                    $promotedPlayer = $matchday->registrations()
                        ->where('id', '!=', $reg->id)
                        ->where('posisi', $reg->posisi)
                        ->whereIn('status', ['waiting_list', 'waiting'])
                        ->orderBy('created_at', 'asc')
                        ->first();

                    // C. Naikkan member reguler tersebut kembali ke Utama
                    if ($promotedPlayer) {
                        $promotedPlayer->update([
                            'status' => 'utama',
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Status prioritas dibatalkan. Antrean matchday telah diperbarui.');
    }
}