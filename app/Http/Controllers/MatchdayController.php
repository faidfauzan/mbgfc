<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use Illuminate\Http\Request;

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
}