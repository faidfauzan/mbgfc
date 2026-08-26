<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom kuota_gk & kuota_player di tabel matchdays
        Schema::table('matchdays', function (Blueprint $table) {
            if (!Schema::hasColumn('matchdays', 'kuota_gk')) {
                // Mengubah after('kuota_peserta') menjadi after('htm') atau after('lokasi')
                $table->integer('kuota_gk')->default(2)->after('htm');
            }
            if (!Schema::hasColumn('matchdays', 'kuota_player')) {
                $table->integer('kuota_player')->default(10)->after('kuota_gk');
            }
        });

        // 2. Pastikan kolom posisi ada di tabel matchday_registrations
        Schema::table('matchday_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('matchday_registrations', 'posisi')) {
                $table->enum('posisi', ['kiper', 'non_kiper'])->default('non_kiper')->after('member_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('matchdays', function (Blueprint $table) {
            $table->dropColumn(['kuota_gk', 'kuota_player']);
        });

        Schema::table('matchday_registrations', function (Blueprint $table) {
            $table->dropColumn('posisi');
        });
    }
};