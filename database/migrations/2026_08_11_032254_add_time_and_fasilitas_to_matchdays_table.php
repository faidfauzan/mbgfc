<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matchdays', function (Blueprint $table) {
            if (!Schema::hasColumn('matchdays', 'jam_mulai')) {
                $table->time('jam_mulai')->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('matchdays', 'jam_selesai')) {
                $table->time('jam_selesai')->nullable()->after('jam_mulai');
            }
            if (!Schema::hasColumn('matchdays', 'durasi_menit')) {
                $table->integer('durasi_menit')->nullable()->after('jam_selesai');
            }
            if (!Schema::hasColumn('matchdays', 'fasilitas')) {
                $table->json('fasilitas')->nullable()->after('kuota');
            }
        });
    }

    public function down(): void
    {
        Schema::table('matchdays', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai', 'durasi_menit', 'fasilitas']);
        });
    }
};