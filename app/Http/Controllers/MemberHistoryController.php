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
        $member = $user->member;

        if (!$member) {
            return back()->with('error', 'Data member tidak ditemukan.');
        }

        // 1. Ambil pendaftaran matchday yang TIDAK dibatalkan dan matchday sudah finished/closed
        $history = MatchdayRegistration::with('matchday')
            ->where('member_id', $member->id)
            ->where('status', '!=', 'batal') // Mencegah data batal muncul double
            ->whereHas('matchday', function ($query) {
                $query->whereIn('status', ['finished', 'closed']);
            })
            ->latest()
            ->paginate(10);

        // 2. Hitung total matchday diikuti (hanya status 'utama')
        $totalMatchdays = MatchdayRegistration::where('member_id', $member->id)
            ->where('status', 'utama')
            ->whereHas('matchday', function ($query) {
                $query->whereIn('status', ['finished', 'closed']);
            })
            ->count();

        // 3. Hitung total HTM terbayar
        $totalHtm = MatchdayRegistration::where('member_id', $member->id)
            ->where('status', 'utama')
            ->whereHas('matchday', function ($query) {
                $query->whereIn('status', ['finished', 'closed']);
            })
            ->with('matchday')
            ->get()
            ->sum(function ($reg) {
                if (!$reg->matchday) return 0;
                $posisi = strtolower($reg->posisi);
                return in_array($posisi, ['kiper', 'gk']) 
                    ? ($reg->matchday->htm_gk ?? 0) 
                    : ($reg->matchday->htm_player ?? 0);
            });

        return view('members.history', compact('history', 'totalMatchdays', 'totalHtm', 'member'));
    }
}