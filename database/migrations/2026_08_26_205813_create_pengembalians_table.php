<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaans')->cascadeOnDelete();
            $table->date('tanggal_pengembalian');
            $table->unsignedBigInteger('diterima_oleh')->nullable();
            $table->text('kondisi_alat')->nullable();
            $table->integer('terlambat_hari')->default(0);
            $table->text('catatan')->nullable();
            $table->enum('status', ['diterima', 'perlu_perbaikan', 'ditolak'])->default('diterima');
            $table->timestamps();

            $table->foreign('diterima_oleh')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
