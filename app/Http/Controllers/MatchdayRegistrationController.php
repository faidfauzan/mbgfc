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

        // Cek status pendaftaran member pada matchday ini
        $registration = MatchdayRegistration::where('matchday_id', $matchday->id)
            ->where('member_id', $member->id)
            ->where('status', '!=', 'batal')
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

        // Ambil posisi dari input form/query, default ke 'non_kiper' jika tidak diisi
        $posisi = $request->input('posisi', 'non_kiper');

        try {
            $registration = $this->registrationService->register($member, $matchday, $posisi);

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
            'posisi'            => 'required|in:kiper,non_kiper',
            'is_prioritas'      => 'required|in:0,1',
            'metode_pembayaran' => 'required|in:cash,qris',
            'bukti_bayar'       => 'required_if:metode_pembayaran,qris|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $member = auth()->user()->member;

        if (!$member) {
            return back()->with('error', 'Profil member tidak ditemukan.');
        }

        try {
            // Panggil Service (Sudah otomatis handle DB lock, prioritas vs umum, & penggeseran kuota)
            $registration = $this->registrationService->register($member, $matchday, $validated['posisi'], $validated['is_prioritas']);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        // Upload bukti bayar jika memilih QRIS
        $path = null;
        if ($request->metode_pembayaran === 'qris' && $request->hasFile('bukti_bayar')) {
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