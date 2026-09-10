<?php

namespace App\Http\Controllers;

use App\Models\MatchdayRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan relasi ke model Member ada
        $memberId = $user->member->id ?? null;

        if (!$memberId) {
            return back()->with('error', 'Data member tidak ditemukan.');
        }

        // Ambil semua pendaftaran matchday milik member ini
        $history = MatchdayRegistration::with('matchday')
            ->where('member_id', $memberId)
            ->whereHas('matchday', function ($query) {
                // Opsional: Hanya tampilkan matchday yang sudah selesai (CLOSED)
                $query->where('status', 'CLOSED');
            })
            ->latest()
            ->paginate(10);

        // Hitung statistik ringkas untuk member
        $totalMatchdays = MatchdayRegistration::where('member_id', $memberId)
            ->where('status', 'utama')
            ->whereHas('matchday', function ($query) {
                $query->where('status', 'CLOSED');
            })->count();

        $totalHtm = MatchdayRegistration::where('member_id', $memberId)
            ->where('status', 'utama')
            ->whereHas('matchday', function ($query) {
                $query->where('status', 'CLOSED');
            })
            ->get()
            ->sum(function ($reg) {
                return $reg->matchday->htm ?? 0;
            });

        return view('members.history', compact('history', 'totalMatchdays', 'totalHtm'));
    }
}
