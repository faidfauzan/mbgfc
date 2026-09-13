<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriorityTransaction; // 👈 Pindahkan ke atas sini (di luar class)

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'nomor_punggung',
        'posisi',
        'no_hp',
        'foto',
        'tanggal_bergabung',
        'status_aktif',
        'is_prioritas',
        'jenis_member',
        'paket_prioritas',
        'tanggal_mulai_prioritas',
        'tanggal_berakhir_prioritas',
        'bukti_pembayaran_prioritas',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
        'tanggal_mulai_prioritas' => 'date',
        'tanggal_berakhir_prioritas' => 'datetime',
        'status_aktif' => 'boolean',
        'is_prioritas' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matchdayRegistrations()
    {
        return $this->hasMany(MatchdayRegistration::class);
    }

    // Logika pengecekan prioritas masih aktif atau tidak
    public function isPrioritasActive()
    {
        return $this->tanggal_berakhir_prioritas
            && $this->tanggal_berakhir_prioritas->isFuture();
    }

    // Relasi ke tabel riwayat transaksi prioritas
    public function priorityTransactions()
    {
        return $this->hasMany(PriorityTransaction::class);
    }
}