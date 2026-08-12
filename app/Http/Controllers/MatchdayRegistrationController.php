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
     * Member mendaftar ke matchday
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

        // Validasi keamanan: Pastikan yang dibatalkan adalah milik member yang sedang login
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
}