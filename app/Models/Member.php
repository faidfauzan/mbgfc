<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_punggung',
        'posisi',
        'no_hp',
        'foto',
        'tanggal_bergabung',
        'status_aktif',
        'jenis_member',
        'paket_prioritas',
        'tanggal_mulai_prioritas',
        'tanggal_berakhir_prioritas',

    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
        'tanggal_mulai_prioritas' => 'date',
        'tanggal_berakhir_prioritas' => 'date',
        'status_aktif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matchdayRegistrations()
{
    return $this->hasMany(MatchdayRegistration::class);
}
}
