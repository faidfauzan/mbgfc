<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'is_prioritas', // 1. Tambahkan kolom ini
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
        'is_prioritas' => 'boolean', // 2. Tambahkan casting boolean
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matchdayRegistrations()
    {
        return $this->hasMany(MatchdayRegistration::class);
    }

    // 3. Logika pengecekan prioritas masih aktif atau ngga
    public function isPrioritasActive()
    {
        return $this->tanggal_berakhir_prioritas
            && $this->tanggal_berakhir_prioritas->isFuture();
    }
}