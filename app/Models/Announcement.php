<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    // Mass Assignment Protection: Hanya kolom ini yang diizinkan diisi secara massal
    protected $fillable = [
        'judul',
        'isi',
        'dibuat_oleh'
    ];

    // Relasi ke tabel Users (Pembuat Pengumuman)
    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}