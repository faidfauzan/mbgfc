<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Setting;
use App\Models\PriorityTransaction;
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

    // Proses Submit Pendaftaran / Perpanjangan Prioritas
    public function store(Request $request)
    {
        $request->validate([
            'paket_prioritas'   => 'required|in:1_bulan,2_bulan,6_bulan,1_tahun',
            'metode_pembayaran' => 'required|in:qris,cash',
            'bukti_pembayaran'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
        $namaPaket = 'Paket Bulanan';
        $harga = 50000; // Sesuaikan nominal asli di sistem kamu

        switch ($request->paket_prioritas) {
            case '1_bulan':
                $expiredAt = $startDate->copy()->addMonth();
                $namaPaket = 'Paket Bulanan';
                $harga = 50000;
                break;
            case '2_bulan':
                $expiredAt = $startDate->copy()->addMonths(2);
                $namaPaket = 'Paket 2 Bulan';
                $harga = 100000;
                break;
            case '6_bulan':
                $expiredAt = $startDate->copy()->addMonths(6);
                $namaPaket = 'Paket 6 Bulan';
                $harga = 275000;
                break;
            case '1_tahun':
                $expiredAt = $startDate->copy()->addYear();
                $namaPaket = 'Paket Tahunan';
                $harga = 500000;
                break;
        }

        // 1. Update Member Utama (Untuk Status Active & Pengingat H-7)
        $member->update([
            'jenis_member'               => 'Prioritas',
            'is_prioritas'               => true,
            'paket_prioritas'            => $request->paket_prioritas,
            'tanggal_mulai_prioritas'    => $startDate,
            'tanggal_berakhir_prioritas' => $expiredAt,
            'bukti_pembayaran_prioritas' => $pathBukti,
        ]);

        // 2. SIMPAN LOG KE RIWAYAT TRANSAKSI (Agar Data Lama Tidak Tertimpa)
        PriorityTransaction::create([
            'member_id'         => $member->id,
            'paket'             => $namaPaket,
            'periode_mulai'     => $startDate,
            'periode_selesai'   => $expiredAt,
            'metode_pembayaran' => strtoupper($request->metode_pembayaran),
            'jumlah'            => $harga,
            'status'            => 'lunas',
            'bukti_pembayaran'  => $pathBukti,
        ]);

        return redirect()->route('prioritas.history')->with('success', 'Selamat! Anda telah resmi menjadi Member Prioritas kami. Terima kasih atas kepercayaan Anda.');
    }

    // Halaman Riwayat Transaksi Prioritas Member
    public function history()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        $isPrioritasAktif = false;
        $sisaHari = 0;

        if ($member && $member->isPrioritasActive() && $member->tanggal_berakhir_prioritas) {
            $isPrioritasAktif = true;
            $sisaHari = max(0, (int) now()->diffInDays($member->tanggal_berakhir_prioritas, false));
        }

        // Ambil riwayat transaksi milik member ini
        $transactions = $member 
            ? PriorityTransaction::where('member_id', $member->id)->latest()->paginate(10)
            : collect();

        return view('members.prioritas.history', compact('member', 'isPrioritasAktif', 'sisaHari', 'transactions'));
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