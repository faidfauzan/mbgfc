<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use Illuminate\Http\Request;
use App\Models\MatchdayRegistration;
use App\Services\MatchdayRegistrationService;
use Illuminate\Support\Facades\Storage;

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
            'poster'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // max 2MB
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
            'durasi_menit'   => 'nullable|integer',
            'lokasi'         => 'required|string|max:255',
            'htm'            => 'required|numeric|min:0',
            'kuota'          => 'required|integer|min:1',
            'fasilitas'      => 'nullable|array',
            'fasilitas.*'    => 'string',
            'catatan'        => 'nullable|string',
            'status'         => 'required|in:open,closed,finished',
        ]);

        // Simpan file poster jika ada yang di-upload
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

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
            'htm'            => 'required|numeric|min:0',
            'kuota'          => 'required|integer|min:1',
            'fasilitas'      => 'nullable|array',
            'fasilitas.*'    => 'string',
            'catatan'        => 'nullable|string',
            'status'         => 'required|in:open,closed,finished',
        ]);

        $validated['fasilitas'] = $request->input('fasilitas', []);

        // Jika user meng-upload poster baru
        if ($request->hasFile('poster')) {
            // Hapus file poster lama dari storage jika ada
            if ($matchday->poster && Storage::disk('public')->exists($matchday->poster)) {
                Storage::disk('public')->delete($matchday->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $matchday->update($validated);

        return redirect()->route('matchdays.index')
            ->with('success', 'Matchday berhasil diperbarui!');
    }

    public function destroy(Matchday $matchday)
    {
        // Hapus file poster dari storage jika matchday dihapus
        if ($matchday->poster && Storage::disk('public')->exists($matchday->poster)) {
            Storage::disk('public')->delete($matchday->poster);
        }

        $matchday->delete();

        return redirect()->route('matchdays.index')
            ->with('success', 'Matchday berhasil dihapus!');
    }

    // Nampilin daftar peserta yang terdaftar di matchday tertentu.
    public function peserta(Matchday $matchday)
    {
        $registrations = $matchday->registrations()
            ->with(['member.user'])
            ->where('status', '!=', 'batal')
            ->orderByRaw("FIELD(status, 'utama', 'waiting_list')")
            ->orderBy('waktu_daftar', 'asc')
            ->get();

        return view('matchdays.peserta', compact('matchday', 'registrations'));
    }

    // Batalkan pendaftaran peserta secara manual oleh Captain.
    public function batalkanPaksa(MatchdayRegistration $registration, MatchdayRegistrationService $service)
    {
        $service->cancel($registration);

        return back()->with('success', 'Pendaftaran peserta berhasil dibatalkan oleh Captain.');
    }
}