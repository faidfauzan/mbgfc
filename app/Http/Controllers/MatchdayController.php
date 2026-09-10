<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use Illuminate\Http\Request;
use App\Models\MatchdayRegistration;
use App\Services\MatchdayRegistrationService;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class MatchdayController extends Controller
{
    public function index()
    {
        $matchdays = Matchday::latest()->paginate(10);
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
            'nama_matchday'  => 'required|string|max:255',
            'poster'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
            'durasi_menit'   => 'nullable|integer',
            'lokasi'         => 'required|string|max:255',
            'htm_gk'         => 'required|numeric|min:0',
            'htm_player'     => 'required|numeric|min:0',
            'kuota_gk'       => 'required|integer|min:0',
            'kuota_player'   => 'required|integer|min:0',
            'fasilitas'      => 'nullable|array',
            'fasilitas.*'    => 'string',
            'catatan'        => 'nullable|string',
            'status'         => 'required|in:open,closed,finished',
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
            'nama_matchday'  => 'required|string|max:255',
            'poster'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
            'durasi_menit'   => 'nullable|integer',
            'lokasi'         => 'required|string|max:255',
            'htm_player'     => 'required|numeric|min:0',
            'htm_gk'         => 'required|numeric|min:0',
            'kuota_gk'       => 'required|integer|min:0',
            'kuota_player'   => 'required|integer|min:0',
            'fasilitas'      => 'nullable|array',
            'fasilitas.*'    => 'string',
            'catatan'        => 'nullable|string',
            'status'         => 'required|in:open,closed,finished',
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

        // Jalanin sinkronisasi urutan peserta after kuota diubah admin
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

    public function peserta(Matchday $matchday)
    {
        $registrations = $matchday->registrations()
            ->with(['member.user'])
            ->where('status', '!=', 'batal')
            ->orderByRaw("FIELD(status, 'utama', 'waiting_list')")
            ->orderByRaw("CASE WHEN is_prioritas = 1 OR LOWER(tipe_member_saat_daftar) = 'prioritas' THEN 0 ELSE 1 END ASC")
            ->orderBy('waktu_daftar', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('matchdays.peserta', compact('matchday', 'registrations'));
    }

    public function batalkanPaksa(MatchdayRegistration $registration, MatchdayRegistrationService $service)
    {
        $service->cancel($registration);

        return back()->with('success', 'Pendaftaran peserta berhasil dibatalkan oleh Captain.');
    }

    /**
     * Menyinkronkan status peserta (utama vs waiting_list) berdasarkan kuota
     * Menggunakan sorting konsisten: Prioritas 1 Status Member (Prioritas > Umum), Prioritas 2 Waktu Daftar (ASC)
     */
    private function syncParticipantStatuses(Matchday $matchday)
    {
        // 1. Sinkronisasi Peserta Posisi Kiper (GK / kiper)
        $this->adjustQuotaByPosisi($matchday, ['gk', 'kiper'], $matchday->kuota_gk);

        // 2. Sinkronisasi Peserta Posisi Pemain (PLAYER / player / pemain / non_kiper)
        $this->adjustQuotaByPosisi($matchday, ['player', 'pemain', 'non_kiper'], $matchday->kuota_player);
    }

    private function adjustQuotaByPosisi(Matchday $matchday, array $posisiKeys, int $quota)
    {
        $registrations = $matchday->registrations()
            ->where(function($query) use ($posisiKeys) {
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
            // Jika urutan berdasarkan prioritas & waktu mendaftar masih dalam batas kuota -> 'utama'
            // Jika melebihi kuota -> 'waiting_list'
            $newStatus = ($index < $quota) ? 'utama' : 'waiting_list';

            if ($registration->status !== $newStatus) {
                $registration->update(['status' => $newStatus]);
            }
        }
    }

    // 1. Menampilkan daftar matchday yang sudah selesai
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
            ->latest('tanggal')
            ->paginate(10);

        return view('history.matchdays', compact('matchdays'));
    }

    // 2. Menampilkan detail histori partisipasi member tertentu
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