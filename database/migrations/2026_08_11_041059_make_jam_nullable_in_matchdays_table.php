<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matchdays', function (Blueprint $table) {
            if (Schema::hasColumn('matchdays', 'jam')) {
                $table->string('jam')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('matchdays', function (Blueprint $table) {
            if (Schema::hasColumn('matchdays', 'jam')) {
                $table->string('jam')->nullable(false)->change();
            }
        });
    }
};