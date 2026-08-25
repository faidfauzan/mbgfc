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
     * Member mendaftar ke matchday (Langsung / Simple)
     */
    public function daftar(Matchday $matchday)
    {
        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Profil member Anda tidak ditemukan.');
        }

        try {
            $registration = $this->registrationService->register($member, $matchday);

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
     * FASE 3: Tampilkan halaman form pendaftaran (posisi, bayar, qris)
     */
    public function create(Matchday $matchday)
    {
        return view('matchdays.member-register', compact('matchday'));
    }

    /**
     * FASE 3: Simpan pendaftaran beserta upload bukti bayar
     */
    public function store(Request $request, Matchday $matchday)
    {
        $request->validate([
            'posisi'            => 'required|in:kiper,non_kiper',
            'is_prioritas'      => 'required|boolean',
            'metode_pembayaran' => 'required|in:cash,qris',
            'bukti_bayar'       => 'required_if:metode_pembayaran,qris|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $member = auth()->user()->member;

        // Hitung kuota terisi
        $totalUtama = MatchdayRegistration::where('matchday_id', $matchday->id)
            ->where('status', 'utama')
            ->count();

        $status = ($totalUtama < $matchday->kuota_peserta) ? 'utama' : 'waiting_list';

        // Upload file hanya jika memilih QRIS dan menyertakan file
        $path = null;
        if ($request->metode_pembayaran === 'qris' && $request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
        }

        MatchdayRegistration::create([
            'matchday_id'       => $matchday->id,
            'member_id'         => $member->id,
            'posisi'            => $request->posisi,
            'is_prioritas'      => $request->is_prioritas,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status'            => $status,
            'bukti_bayar'       => $path,
        ]);

        return redirect()->route('matchday.member.show', $matchday)
            ->with('success', 'Pendaftaran berhasil! Status Anda: ' . strtoupper($status));
    }
}