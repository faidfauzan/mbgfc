<?php

namespace App\Services;

use App\Models\Matchday;
use App\Models\MatchdayRegistration;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Exception;

class MatchdayRegistrationService
{
    public function register(Member $member, Matchday $matchday)
    {
        return DB::transaction(function () use ($member, $matchday) {
            // Lock row matchday untuk cegah race condition
            $matchday = Matchday::where('id', $matchday->id)->lockForUpdate()->first();

            if ($matchday->status !== 'open') {
                throw new Exception('Pendaftaran untuk matchday ini sudah ditutup.');
            }

            // Cek apakah member sudah mendaftar (yang belum dibatalkan masa aktif nya)
            $existing = MatchdayRegistration::where('matchday_id', $matchday->id)
                ->where('member_id', $member->id)
                ->where('status', '!=', 'batal')
                ->first();

            if ($existing) {
                throw new Exception('Kamu sudah terdaftar pada matchday ini.');
            }

            // Hitung peserta dengan status 'utama' saat ini
            $utamaCount = MatchdayRegistration::where('matchday_id', $matchday->id)
                ->where('status', 'utama')
                ->count();

            $isPrioritas = $member->jenis_member === 'prioritas';
            $tipeSaatDaftar = $isPrioritas ? 'prioritas' : 'umum';

            // Skenario A: Kuota Masih Ada
            if ($utamaCount < $matchday->kuota) {
                return MatchdayRegistration::create([
                    'matchday_id' => $matchday->id,
                    'member_id'   => $member->id,
                    'status'      => 'utama',
                    'tipe_member_saat_daftar' => $tipeSaatDaftar,
                    'waktu_daftar' => now(),
                ]);
            }

            // Skenario B: Kuota Penuh & Member Biasa (Umum) -> Masuk Waiting List
            if (!$isPrioritas) {
                return MatchdayRegistration::create([
                    'matchday_id' => $matchday->id,
                    'member_id'   => $member->id,
                    'status'      => 'waiting_list',
                    'tipe_member_saat_daftar' => $tipeSaatDaftar,
                    'waktu_daftar' => now(),
                ]);
            }

            // Skenario C: Kuota Penuh & Member PRIORITAS -> Geser member 'umum' terakhir
            $lastUmum = MatchdayRegistration::where('matchday_id', $matchday->id)
            ->where('status', 'utama')
            ->where('tipe_member_saat_daftar', 'umum')
            ->orderByDesc('waktu_daftar')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

            if ($lastUmum) {
                // sisterem ini menuurunkan member umum pendaftar terakhir ke waiting list
                $lastUmum->update(['status' => 'waiting_list']);

                // Masukkan member prioritas ke skuad utama
                return MatchdayRegistration::create([
                    'matchday_id' => $matchday->id,
                    'member_id'   => $member->id,
                    'status'      => 'utama',
                    'tipe_member_saat_daftar' => $tipeSaatDaftar,
                    'waktu_daftar' => now(),
                ]);
            }

            // Jika kuota penuh oleh SEMUA member prioritas, prioritas baru tetap ke waiting list
            return MatchdayRegistration::create([
                'matchday_id' => $matchday->id,
                'member_id'   => $member->id,
                'status'      => 'waiting_list',
                'tipe_member_saat_daftar' => $tipeSaatDaftar,
                'waktu_daftar' => now(),
            ]);
        });
    }

public function cancel(MatchdayRegistration $registration)
    {
        return DB::transaction(function () use ($registration) {
            
            $wasUtama = ($registration->status === 'utama');

            // 1. Ubah status registrasi ini jadi 'batal'
            $registration->update(['status' => 'batal']);

            // 2. Jika yang batal adalah skuad utama, naikkan 1 orang dari waiting list
            if ($wasUtama) {
               $nextInLine = MatchdayRegistration::where('matchday_id', $registration->matchday_id)
                ->where('status', 'waiting_list')
                ->orderByRaw("FIELD(tipe_member_saat_daftar, 'prioritas', 'umum') ASC")
                ->orderBy('waktu_daftar', 'asc')
                ->orderBy('id', 'asc')   // ← tambahan ini
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