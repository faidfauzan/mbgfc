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
        $totalMembers = Member::count();
        $totalMatchdays = Matchday::count();

        // PERBAIKAN: Menggunakan nama kolom baru (tanggal_berakhir_prioritas)
        $totalPrioritas = Member::whereNotNull('tanggal_berakhir_prioritas')
            ->where('tanggal_berakhir_prioritas', '>', now())
            ->count();
            
        $totalReguler = $totalMembers - $totalPrioritas;

        // Ambil setting kuota
        $maxQuota = (int) (Setting::where('key', 'max_prioritas_quota')->value('value') ?? 15);
        $activePrioritasCount = $totalPrioritas;
        $isQuotaFull = $activePrioritasCount >= $maxQuota;

        // Data member logged in
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