<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaans')->cascadeOnDelete();
            $table->string('kode_pembayaran')->unique();
            $table->date('tanggal_pembayaran');
            $table->decimal('jumlah', 14, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris']);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'diverifikasi', 'ditolak'])->default('pending');
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('diverifikasi_oleh')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
