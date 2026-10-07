<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use App\Models\MatchdayRegistration;
use App\Models\Member;
use App\Models\User;
use App\Services\MatchdayRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MatchdayController extends Controller
{
    protected $registrationService;

    // Inject MatchdayRegistrationService di Constructor
    public function __construct(MatchdayRegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function index()
    {
        $matchdays = Matchday::whereIn('status', ['open', 'closed'])
            ->latest('tanggal')
            ->paginate(10);

        return view('matchdays.index', compact('matchdays'));
    }

    public function create()
    {
        return view('matchdays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_matchday' => 'required|string|unique:matchdays,nomor_matchday',
            'nama_matchday' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'durasi_menit' => 'nullable|integer',
            'lokasi' => 'required|string|max:255',
            'htm_gk' => 'required|numeric|min:0',
            'htm_player' => 'required|numeric|min:0',
            'kuota_gk' => 'required|integer|min:0',
            'kuota_player' => 'required|integer|min:0',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string',
            'catatan' => 'nullable|string',
            'status' => 'required|in:open,closed,finished',
        ]);

        $validated['kuota'] = $validated['kuota_gk'] + $validated['kuota_player'];

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $validated['fasilitas'] = $request->input('fasilitas', []);

        Matchday::create($validated);

        return redirect()->route('matchdays.index')
            ->with('success', 'Matchday berhasil ditambahkan!');
    }

    public function edit(Matchday $matchday)
    {
        return view('matchdays.edit', compact('matchday'));
    }

    public function update(Request $request, Matchday $matchday)
    {
        $validated = $request->validate([
            'nomor_matchday' => 'required|string|unique:matchdays,nomor_matchday,' . $matchday->id,
            'nama_matchday' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'durasi_menit' => 'nullable|integer',
            'lokasi' => 'required|string|max:255',
            'htm_player' => 'required|numeric|min:0',
            'htm_gk' => 'required|numeric|min:0',
            'kuota_gk' => 'required|integer|min:0',
            'kuota_player' => 'required|integer|min:0',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string',
            'catatan' => 'nullable|string',
            'status' => 'required|in:open,closed,finished',
        ]);

        $validated['kuota'] = $validated['kuota_gk'] + $validated['kuota_player'];
        $validated['fasilitas'] = $request->input('fasilitas', []);

        if ($request->hasFile('poster')) {
            if ($matchday->poster && Storage::disk('public')->exists($matchday->poster)) {
                Storage::disk('public')->delete($matchday->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $matchday->update($validated);

        $this->syncParticipantStatuses($matchday);

        return redirect()->route('matchdays.index')
            ->with('success', 'Matchday dan penyesuaian kuota peserta berhasil diperbarui!');
    }

    public function destroy(Matchday $matchday)
    {
        if ($matchday->poster && Storage::disk('public')->exists($matchday->poster)) {
            Storage::disk('public')->delete($matchday->poster);
        }

        $matchday->delete();

        return redirect()->route('matchdays.index')
            ->with('success', 'Matchday berhasil dihapus!');
    }

    // --- FITUR KELOLA PESERTA MATCHDAY ---
    public function peserta(Matchday $matchday)
    {
        // 1. Load data peserta yang sudah terdaftar
        $registrations = $matchday->registrations()
            ->with(['member.user'])
            ->where('status', '!=', 'batal')
            ->orderByRaw("FIELD(status, 'utama', 'waiting_list')")
            ->orderByRaw("CASE WHEN is_prioritas = 1 OR LOWER(tipe_member_saat_daftar) = 'prioritas' THEN 0 ELSE 1 END ASC")
            ->orderBy('waktu_daftar', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // 2. Ambil ID member yang sudah terdaftar agar tidak muncul di modal pilihan
        $registeredMemberIds = $registrations->pluck('member_id')->toArray();

        // 3. Ambil semua member yang BELUM mendaftar DAN HANYA yang ber-role member (BUKAN ADMIN)
        $allMembers = Member::with('user')
            ->whereNotIn('id', $registeredMemberIds)
            ->whereHas('user', function ($query) {
                $query->whereNotIn('role', ['admin', 'captain']);
            })
            ->get();

        return view('matchdays.peserta', compact('matchday', 'registrations', 'allMembers'));
    }

    public function batalkanPaksa(MatchdayRegistration $registration)
    {
        $this->registrationService->cancel($registration);

        return back()->with('success', 'Pendaftaran peserta berhasil dibatalkan oleh Captain.');
    }

    // --- TAMBAH MEMBER JADI PESERTA (OLEH ADMIN) ---

    // Form pendaftaran versi Admin
    public function createRegistrationForMember(Matchday $matchday, Member $member)
    {
        return view('matchdays.admin-register-member', compact('matchday', 'member'));
    }

    // Simpan pendaftaran oleh Admin
    public function storeRegistrationForMember(Request $request, Matchday $matchday, Member $member)
    {
        $validated = $request->validate([
            'posisi' => 'required|in:pemain,kiper',
            'sub_posisi' => 'nullable|in:bek,gelandang,penyerang',
            'metode_pembayaran' => 'required|in:qris,cash',
            'bukti_bayar' => 'required_if:metode_pembayaran,qris|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $subPosisi = $validated['posisi'] === 'pemain' ? ($validated['sub_posisi'] ?? 'bek') : null;

        try {
            $registration = $this->registrationService->register($member, $matchday, $validated['posisi'], $subPosisi);

            $path = null;
            if ($validated['metode_pembayaran'] === 'qris' && $request->hasFile('bukti_bayar')) {
                $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
            }

            $registration->update([
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'bukti_bayar' => $path,
            ]);

            return redirect()->route('matchdays.peserta', $matchday->id)
                ->with('success', "Berhasil mendaftarkan {$member->user->name} ke Matchday!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function syncParticipantStatuses(Matchday $matchday)
    {
        $this->adjustQuotaByPosisi($matchday, ['gk', 'kiper'], $matchday->kuota_gk);
        $this->adjustQuotaByPosisi($matchday, ['player', 'pemain', 'non_kiper'], $matchday->kuota_player);
    }

    private function adjustQuotaByPosisi(Matchday $matchday, array $posisiKeys, int $quota)
    {
        $registrations = $matchday->registrations()
            ->where(function ($query) use ($posisiKeys) {
                foreach ($posisiKeys as $pos) {
                    $query->orWhereRaw('LOWER(posisi) = ?', [strtolower($pos)]);
                }
            })
            ->where('status', '!=', 'batal')
            ->orderByRaw("CASE WHEN is_prioritas = 1 OR LOWER(tipe_member_saat_daftar) = 'prioritas' THEN 0 ELSE 1 END ASC")
            ->orderBy('waktu_daftar', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($registrations as $index => $registration) {
            $newStatus = ($index < $quota) ? 'utama' : 'waiting_list';

            if ($registration->status !== $newStatus) {
                $registration->update(['status' => $newStatus]);
            }
        }
    }

    public function historyMatchday()
    {
        $matchdays = Matchday::where('status', 'finished')
            ->withCount([
                'registrations as total_utama' => function ($q) {
                    $q->where('status', 'utama');
                },
                'registrations as total_waiting' => function ($q) {
                    $q->where('status', 'waiting_list');
                }
            ])
            ->with([
                'registrations' => function ($q) {
                    $q->where('status', 'utama');
                }
            ])
            ->latest('tanggal')
            ->paginate(10);

        $matchdays->getCollection()->transform(function ($matchday) {
            $matchday->estimasi_htm = $matchday->registrations
                ->where('status', 'utama')
                ->sum(function ($reg) use ($matchday) {
                    $posisi = strtolower($reg->posisi ?? '');
                    $isGk = in_array($posisi, ['kiper', 'gk', 'kiper (gk)']);

                    return $isGk
                        ? ($matchday->htm_gk ?? 0)
                        : ($matchday->htm_player ?? 0);
                });

            return $matchday;
        });

        return view('history.matchdays', compact('matchdays'));
    }

    public function historyMember(User $user)
    {
        $registrations = MatchdayRegistration::whereHas('member', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with('matchday')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('history.member-detail', compact('user', 'registrations'));
    }
}