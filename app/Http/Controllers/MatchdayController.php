<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use Illuminate\Http\Request;
use App\Models\MatchdayRegistration;
use App\Services\MatchdayRegistrationService;

class MatchdayController extends Controller
{
    public function index()
{
    $matchdays = Matchday::latest()->paginate(10); // Ubah get() jadi paginate()
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

        $matchday->update($validated);

        return redirect()->route('matchdays.index')
            ->with('success', 'Matchday berhasil diperbarui!');
    }

    public function destroy(Matchday $matchday)
    {
        $matchday->delete();

        return redirect()->route('matchdays.index')
            ->with('success', 'Matchday berhasil dihapus!');
    }

    //nampilin daftar peserta yang terdaftar di matchday tertentu.
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

//Batalkan pendaftaran peserta secara manual oleh Captain.
 
public function batalkanPaksa(MatchdayRegistration $registration, MatchdayRegistrationService $service)
{
    // Memanggil logic service yang sama agar pendaftar waiting list otomatis naik ke skuad utama
    $service->cancel($registration);

    return back()->with('success', 'Pendaftaran peserta berhasil dibatalkan oleh Captain.');
}
}