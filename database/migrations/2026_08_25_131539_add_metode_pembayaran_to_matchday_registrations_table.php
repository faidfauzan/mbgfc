<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('matchday_registrations', function (Blueprint $table) {
        $table->enum('metode_pembayaran', ['cash', 'qris'])->default('qris')->after('is_prioritas');
        // Buat bukti_bayar jadi nullable karena kalau Cash tidak ada bukti bayar
        $table->string('bukti_bayar')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('matchday_registrations', function (Blueprint $table) {
        $table->dropColumn('metode_pembayaran');
    });
}
};
