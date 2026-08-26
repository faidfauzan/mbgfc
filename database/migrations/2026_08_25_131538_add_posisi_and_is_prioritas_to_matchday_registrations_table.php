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
            $table->enum('posisi', ['kiper', 'non_kiper'])->default('non_kiper');
            $table->boolean('is_prioritas')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matchday_registrations', function (Blueprint $table) {
            $table->dropColumn(['posisi', 'is_prioritas']);
        });
    }
};
