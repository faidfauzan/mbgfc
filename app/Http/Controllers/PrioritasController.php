<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class PrioritasController extends Controller
{
    // Halaman Form Pendaftaran Prioritas Member
    public function create()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        // Cek Kuota Maksimal
        $maxQuota = (int) (Setting::where('key', 'max_prioritas_quota')->value('value') ?? 15);
        $activePrioritasCount = Member::whereNotNull('tanggal_berakhir_prioritas')
            ->where('tanggal_berakhir_prioritas', '>', now())
            ->count();

        if ($activePrioritasCount >= $maxQuota && (!$member || !$member->isPrioritasActive())) {
            return redirect()->route('dashboard')->with('error', 'Maaf, kuota Member Prioritas saat ini sudah penuh! next time aja ya hehe :)');
        }

        return view('members.prioritas.create', compact('member', 'maxQuota', 'activePrioritasCount'));
    }

    // Proses Submit Pendaftaran Prioritas
    public function store(Request $request)
    {
        $request->validate([
            'paket_prioritas' => 'required|in:1_bulan,2_bulan,6_bulan,1_tahun',
            'metode_pembayaran' => 'required|in:qris,cash',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        // Double check kuota (pakai tanggal_berakhir_prioritas)
        $maxQuota = (int) (Setting::where('key', 'max_prioritas_quota')->value('value') ?? 15);
        $activePrioritasCount = Member::whereNotNull('tanggal_berakhir_prioritas')
            ->where('tanggal_berakhir_prioritas', '>', now())
            ->count();

        if ($activePrioritasCount >= $maxQuota && !$member->isPrioritasActive()) {
            return back()->with('error', 'Pendaftaran gagal, kuota Member Prioritas sudah penuh!');
        }

        // Upload bukti bayar jika ada
        $pathBukti = $member->bukti_pembayaran_prioritas;
        if ($request->hasFile('bukti_pembayaran')) {
            $pathBukti = $request->file('bukti_pembayaran')->store('bukti_prioritas', 'public');
        }

        // Hitung Tanggal Expired (pakai tanggal_berakhir_prioritas)
        $startDate = ($member->tanggal_berakhir_prioritas && $member->tanggal_berakhir_prioritas->isFuture())
            ? $member->tanggal_berakhir_prioritas
            : now();

        $expiredAt = now();
        switch ($request->paket_prioritas) {
            case '1_bulan':
                $expiredAt = $startDate->copy()->addMonth();
                break;
            case '2_bulan':
                $expiredAt = $startDate->copy()->addMonths(2);
                break;
            case '6_bulan':
                $expiredAt = $startDate->copy()->addMonths(6);
                break;
            case '1_tahun':
                $expiredAt = $startDate->copy()->addYear();
                break;
        }

        // Update Member (Diselaraskan dengan Admin & Model)
        $member->update([
            'jenis_member'               => 'Prioritas', // Pakai 'P' Kapital agar sama dengan tabel Admin
            'is_prioritas'               => true,        // Set true untuk mengaktifkan method isPrioritasActive()
            'paket_prioritas'            => $request->paket_prioritas,
            'tanggal_mulai_prioritas'    => now(),
            'tanggal_berakhir_prioritas' => $expiredAt,
            'bukti_pembayaran_prioritas' => $pathBukti,
        ]);

        return redirect()->route('dashboard')->with('success', 'Selamat! Anda telah resmi menjadi Member Prioritas kami. Terima kasih atas kepercayaan anda');
    }

    // Update Kuota Prioritas oleh Admin
    public function updateQuota(Request $request)
    {
        $request->validate([
            'max_quota' => 'required|integer|min:0',
        ]);

        Setting::updateOrCreate(
            ['key' => 'max_prioritas_quota'],
            ['value' => $request->max_quota]
        );

        return back()->with('success', 'Batas kuota Member Prioritas berhasil diperbarui!');
    }
}