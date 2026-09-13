<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('priority_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('paket');
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->string('metode_pembayaran');
            $table->decimal('jumlah', 12, 2);
            $table->enum('status', ['lunas', 'pending', 'ditolak'])->default('lunas');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('priority_transactions');
    }
};