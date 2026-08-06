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
    Schema::create('members', function (Blueprint $table) {
        
    $table->id();

    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->unsignedInteger('nomor_punggung')->nullable();

    $table->string('posisi')->nullable();

    $table->string('no_hp')->nullable();

    $table->string('foto')->nullable();

    $table->date('tanggal_bergabung')->nullable();

    $table->boolean('status_aktif')->default(true);

    $table->enum('jenis_member', ['umum', 'prioritas'])->default('umum');

    $table->timestamps();

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
