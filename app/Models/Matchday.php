<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matchday extends Model
{
    protected $fillable = [
        'nomor_matchday',
        'nama_matchday',
        'poster',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'durasi_menit',
        'lokasi',
        'htm',
        'kuota',
        'fasilitas',
        'catatan',
        'status',
    ];

    protected $casts = [
        'fasilitas' => 'array', // Mengubah JSON dari database menjadi Array PHP otomatis
        'tanggal'   => 'date',
    ];

    public function registrations()
    {
        return $this->hasMany(MatchdayRegistration::class);
    }
}