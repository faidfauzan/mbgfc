<?php

namespace App\Services;

use App\Models\Matchday;
use App\Models\MatchdayRegistration;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Exception;

class MatchdayRegistrationService
{
    public function register(Member $member, Matchday $matchday, string $posisi, ?string $subPosisi = null): MatchdayRegistration
    {
        return DB::transaction(function () use ($member, $matchday, $posisi, $subPosisi) {
            $matchday = Matchday::where('id', $matchday->id)->lockForUpdate()->first();

            if ($matchday->status !== 'open') {
                throw new Exception('Pendaftaran untuk matchday ini sudah ditutup.');
            }

            $existing = MatchdayRegistration::where('matchday_id', $matchday->id)
                ->where('member_id', $member->id)
                ->where('status', '!=', 'batal')
                ->first();

            if ($existing) {
                throw new Exception('Kamu sudah terdaftar pada matchday ini.');
            }

            // Normalisasi posisi
            $posisiClean = strtolower(trim($posisi));
            $isGk = in_array($posisiClean, ['kiper', 'gk']);

            $kuotaPosisi = $isGk ? $matchday->kuota_gk : $matchday->kuota_player;
            $htm = $isGk ? $matchday->htm_gk : $matchday->htm_player;

            $utamaPosisiCount = MatchdayRegistration::where('matchday_id', $matchday->id)
                ->where('status', 'utama')
                ->where('posisi', $posisiClean)
                ->count();

            $isPrioritas = ($member->jenis_member ?? 'reguler') === 'prioritas';
            $tipeSaatDaftar = $isPrioritas ? 'prioritas' : 'umum';

            // Skenario A: Kuota posisi ini masih ada
            if ($utamaPosisiCount < $kuotaPosisi) {
                return MatchdayRegistration::create([
                    'matchday_id' => $matchday->id,
                    'member_id' => $member->id,
                    'posisi' => $posisiClean,
                    'sub_posisi' => $subPosisi,
                    'htm' => $htm,
                    'is_prioritas' => $isPrioritas,
                    'status' => 'utama',
                    'tipe_member_saat_daftar' => $tipeSaatDaftar,
                    'waktu_daftar' => now(),
                ]);
            }

            // Skenario B: Kuota posisi penuh, member umum -> waiting list
            if (!$isPrioritas) {
                return MatchdayRegistration::create([
                    'matchday_id' => $matchday->id,
                    'member_id' => $member->id,
                    'posisi' => $posisiClean,
                    'sub_posisi' => $subPosisi,
                    'htm' => $htm,
                    'is_prioritas' => $isPrioritas,
                    'status' => 'waiting_list',
                    'tipe_member_saat_daftar' => $tipeSaatDaftar,
                    'waktu_daftar' => now(),
                ]);
            }

            // Skenario C: Kuota posisi penuh, member prioritas -> geser member umum pendaftar terakhir
            $lastUmum = MatchdayRegistration::where('matchday_id', $matchday->id)
                ->where('status', 'utama')
                ->where('posisi', $posisiClean)
                ->where('tipe_member_saat_daftar', 'umum')
                ->orderByDesc('waktu_daftar')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if ($lastUmum) {
                $lastUmum->update(['status' => 'waiting_list']);

                return MatchdayRegistration::create([
                    'matchday_id' => $matchday->id,
                    'member_id' => $member->id,
                    'posisi' => $posisiClean,
                    'sub_posisi' => $subPosisi,
                    'htm' => $htm,
                    'is_prioritas' => $isPrioritas,
                    'status' => 'utama',
                    'tipe_member_saat_daftar' => $tipeSaatDaftar,
                    'waktu_daftar' => now(),
                ]);
            }

            // Semua pendaftar di posisi ini sudah prioritas -> masuk waiting list
            return MatchdayRegistration::create([
                'matchday_id' => $matchday->id,
                'member_id' => $member->id,
                'posisi' => $posisiClean,
                'sub_posisi' => $subPosisi,
                'htm' => $htm,
                'is_prioritas' => $isPrioritas,
                'status' => 'waiting_list',
                'tipe_member_saat_daftar' => $tipeSaatDaftar,
                'waktu_daftar' => now(),
            ]);
        });
    }

    public function cancel(MatchdayRegistration $registration)
    {
        return DB::transaction(function () use ($registration) {
            $wasUtama = ($registration->status === 'utama');
            $posisi = $registration->posisi;
            $matchdayId = $registration->matchday_id;

            $registration->update(['status' => 'batal']);

            if ($wasUtama) {
                // Promosikan dari waiting list di posisi yang sama
                $nextInLine = MatchdayRegistration::where('matchday_id', $matchdayId)
                    ->where('posisi', $posisi)
                    ->where('status', 'waiting_list')
                    ->orderByRaw("CASE WHEN is_prioritas = 1 OR LOWER(tipe_member_saat_daftar) = 'prioritas' THEN 0 ELSE 1 END ASC")
                    ->orderBy('waktu_daftar', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc')
                    ->lockForUpdate()
                    ->first();

                if ($nextInLine) {
                    $nextInLine->update(['status' => 'utama']);
                }
            }

            return true;
        });
    }
}