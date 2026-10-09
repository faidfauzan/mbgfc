<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriorityTransaction;

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

    /**
     * Logika pengecekan prioritas masih aktif atau tidak.
     * Mengembalikan true jika tanggal berakhir masih di masa depan atau hari ini.
     */
    public function isPrioritasActive(): bool
    {
        if (strtolower($this->jenis_member ?? '') !== 'prioritas') {
            return false;
        }

        if (!$this->tanggal_berakhir_prioritas) {
            return false;
        }

        return $this->tanggal_berakhir_prioritas->isFuture() || $this->tanggal_berakhir_prioritas->isToday();
    }

    /**
     * Accessor untuk mendapatkan teks label jenis member secara otomatis ('Prioritas' atau 'Umum').
     * Bisa dipanggil langsung di Blade: {{ $member->jenis_member_label }}
     */
    public function getJenisMemberLabelAttribute(): string
    {
        return $this->isPrioritasActive() ? 'Prioritas' : 'Umum';
    }

    // Relasi ke tabel riwayat transaksi prioritas
    public function priorityTransactions()
    {
        return $this->hasMany(PriorityTransaction::class);
    }
}