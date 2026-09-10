<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah HTM GK di tabel matchdays
        Schema::table('matchdays', function (Blueprint $table) {
            if (Schema::hasColumn('matchdays', 'htm')) {
                $table->renameColumn('htm', 'htm_player');
            }
            if (!Schema::hasColumn('matchdays', 'htm_gk')) {
                $table->integer('htm_gk')->default(0)->after('htm_player');
            }
        });

        // 2. Tambah kolom penampung nominal terkunci & status bayar
        Schema::table('matchday_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('matchday_registrations', 'nominal_bayar')) {
                $table->integer('nominal_bayar')->default(0)->after('posisi');
            }
            if (!Schema::hasColumn('matchday_registrations', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('nominal_bayar');
            }
            if (!Schema::hasColumn('matchday_registrations', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('pending')->after('metode_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('matchdays', function (Blueprint $table) {
            if (Schema::hasColumn('matchdays', 'htm_player')) {
                $table->renameColumn('htm_player', 'htm');
            }
            if (Schema::hasColumn('matchdays', 'htm_gk')) {
                $table->dropColumn('htm_gk');
            }
        });

        Schema::table('matchday_registrations', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('matchday_registrations', 'nominal_bayar') ? 'nominal_bayar' : null,
                Schema::hasColumn('matchday_registrations', 'status_pembayaran') ? 'status_pembayaran' : null,
            ]));
        });
    }
};