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