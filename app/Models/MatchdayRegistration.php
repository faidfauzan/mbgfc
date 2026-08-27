<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchdayRegistration extends Model
{
    protected $fillable = [
        'matchday_id',
        'member_id',
        'status',
        'tipe_member_saat_daftar',
        'waktu_daftar',
        'posisi',
        'sub_posisi',
        'is_prioritas',
        'metode_pembayaran',
        'bukti_bayar',
    ];

    public function matchday()
    {
        return $this->belongsTo(Matchday::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}