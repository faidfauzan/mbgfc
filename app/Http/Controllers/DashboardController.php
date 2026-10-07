<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Matchday;
use App\Models\Setting;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Hitung TOTAL MEMBER yang BUKAN Admin/Captain
        $totalMembers = Member::whereHas('user', function ($query) {
            $query->whereNotIn('role', ['admin', 'captain']);
        })->count();

        // 2. Hitung Total Matchday
        $totalMatchdays = Matchday::count();

        // 3. Hitung MEMBER PRIORITAS yang BUKAN Admin/Captain dan masa berlaku prioritasnya masih aktif
        $totalPrioritas = Member::whereHas('user', function ($query) {
            $query->whereNotIn('role', ['admin', 'captain']);
        })
        ->whereNotNull('tanggal_berakhir_prioritas')
        ->where('tanggal_berakhir_prioritas', '>=', now()->startOfDay())
        ->count();

        // 4. Hitung MEMBER REGULER (Total Member Murni dikurangi Member Prioritas Aktif)
        $totalReguler = max(0, $totalMembers - $totalPrioritas);

        // Ambil setting kuota prioritas
        $maxQuota = (int) (Setting::where('key', 'max_prioritas_quota')->value('value') ?? 15);
        $activePrioritasCount = $totalPrioritas;
        $isQuotaFull = $activePrioritasCount >= $maxQuota;

        // Data member logged in (jika user yang login adalah member)
        $member = Member::where('user_id', $user->id)->first();

        // Pengumuman 24 jam terakhir
        $pengumumanTerbaru = Announcement::where('created_at', '>=', now()->subHours(24))
            ->latest()
            ->get();

        return view('dashboard', compact(
            'totalMembers',
            'totalMatchdays',
            'totalPrioritas',
            'totalReguler',
            'maxQuota',
            'activePrioritasCount',
            'isQuotaFull',
            'member',
            'pengumumanTerbaru'
        ));
    }
}