<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->timestamp('prioritas_expired_at')->nullable();
            $table->string('bukti_pembayaran_prioritas')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['prioritas_expired_at', 'bukti_pembayaran_prioritas']);
        });
    }
};