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
        Schema::create('matchdays', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_matchday');
            $table->string('nama_matchday');
            $table->date('tanggal');
            $table->time('jam');
            $table->string('lokasi');
            $table->unsignedBigInteger('htm');
            $table->integer('kuota');
            $table->text('catatan')->nullable();
            $table->enum('status', ['open', 'closed', 'finished'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchdays');
    }
};
