<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use App\Models\MatchdayRegistration;
use App\Services\MatchdayRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class MatchdayRegistrationController extends Controller
{
    protected $registrationService;

    public function __construct(MatchdayRegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Menampilkan daftar matchday yang open untuk member
     */
    public function index()
    {
        $member = Auth::user()->member ?? null;

        $matchdays = Matchday::where('status', 'open')->orderBy('tanggal', 'asc')->get();

        $registrations = [];
        if ($member) {
            $registrations = MatchdayRegistration::where('member_id', $member->id)
                ->where('status', '!=', 'batal')
                ->get()
                ->keyBy('matchday_id');
        }

        return view('matchdays.member-index', compact('matchdays', 'registrations', 'member'));
    }

    /**
     * Menampilkan detail matchday
     */
    public function show(Matchday $matchday)
    {
        $member = auth()->user()->member;

        // Load data pendaftar yang VALID saja (bukan yang dibatalkan) beserta data member dan user-nya
        $matchday->load(['registrations' => function ($query) {
            $query->where('status', '!=', 'batal')
                ->with('member.user')
                ->orderByRaw("FIELD(status, 'utama', 'waiting_list')")
                ->orderByRaw("CASE WHEN is_prioritas = 1 OR LOWER(tipe_member_saat_daftar) = 'prioritas' THEN 0 ELSE 1 END ASC")
                ->orderBy('waktu_daftar', 'asc')
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc');
        }]);

        // Cek status pendaftaran member yang sedang login pada matchday ini
        $registration = $matchday->registrations
            ->where('member_id', optional($member)->id)
            ->first();

        return view('matchdays.info-detail', compact('matchday', 'registration'));
    }

    /**
     * Member mendaftar ke matchday (Direct Register - Default Non-Kiper)
     */
    public function daftar(Request $request, Matchday $matchday)
    {
        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Profil member Anda tidak ditemukan.');
        }

        // Ambil posisi dari input form/query, default ke 'pemain' jika tidak diisi
        $posisi = $request->input('posisi', 'pemain');
        $subPosisi = $request->input('sub_posisi', null);

        try {
            $registration = $this->registrationService->register($member, $matchday, $posisi, $subPosisi);

            $msg = $registration->status === 'utama'
                ? 'Berhasil mendaftar Matchday!'
                : 'Kuota utama penuh. Anda masuk ke Waiting List.';

            return back()->with('success', $msg);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Member membatalkan pendaftaran
     */
    public function batal(MatchdayRegistration $registration)
    {
        $member = Auth::user()->member;

        // Validasi keamanan
        if (!$member || $registration->member_id !== $member->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->registrationService->cancel($registration);
            return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membatalkan pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman form pendaftaran (posisi, bayar, qris)
     */
    public function create(Matchday $matchday)
    {
        return view('matchdays.member-register', compact('matchday'));
    }

    /**
     * Simpan pendaftaran via Form (Upload Bukti Bayar / QRIS / Cash)
     */
    public function store(Request $request, Matchday $matchday)
{
    $validated = $request->validate([
        'posisi'            => 'required|in:pemain,kiper',
        'sub_posisi'        => 'nullable|in:bek,gelandang,penyerang',
        'metode_pembayaran' => 'required|in:qris,cash',
        'bukti_bayar'       => 'required_if:metode_pembayaran,qris|nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $member = auth()->user()->member;

    if (!$member) {
        return back()->with('error', 'Profil member tidak ditemukan.');
    }

    // Jika posisi kiper, kosongkan sub_posisi
    $subPosisi = $validated['posisi'] === 'pemain' ? ($validated['sub_posisi'] ?? 'bek') : null;

    try {
        // Panggil Service
        $registration = $this->registrationService->register($member, $matchday, $validated['posisi'], $subPosisi);
    } catch (Exception $e) {
        return back()->with('error', $e->getMessage());
    }

    // Upload bukti bayar jika memilih QRIS
    $path = null;
    if ($validated['metode_pembayaran'] === 'qris' && $request->hasFile('bukti_bayar')) {
        $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
    }

    // Update data pembayaran di record pendaftaran
    $registration->update([
        'metode_pembayaran' => $validated['metode_pembayaran'],
        'bukti_bayar'       => $path,
    ]);

    $pesan = $registration->status === 'utama'
        ? 'Pendaftaran berhasil! Anda masuk ke Skuad UTAMA.'
        : 'Kuota posisi ini penuh. Anda masuk WAITING LIST.';

    return redirect()->route('matchday.member.show', $matchday)->with('success', $pesan);
}
}