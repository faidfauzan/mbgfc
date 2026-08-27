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
        $table->string('posisi')->change();
    });
}

public function down(): void
{
    Schema::table('matchday_registrations', function (Blueprint $table) {
        //
    });
}
};
