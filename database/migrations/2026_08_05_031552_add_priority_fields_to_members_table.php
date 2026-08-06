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
        Schema::table('members', function (Blueprint $table) {
            $table->string('paket_prioritas')->nullable();
            $table->date('tanggal_mulai_prioritas')->nullable();
            $table->date('tanggal_berakhir_prioritas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['paket_prioritas', 'tanggal_mulai_prioritas', 'tanggal_berakhir_prioritas']);
        });
    }
};
