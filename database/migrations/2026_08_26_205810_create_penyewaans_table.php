<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyewaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penyewaan')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('total_hari');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('denda', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->enum('status', [
                'pending',
                'disetujui',
                'ditolak',
                'menunggu_pembayaran',
                'dibayar',
                'sedang_disewa',
                'selesai',
                'dibatalkan',
            ])->default('pending');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();

            $table->foreign('processed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyewaans');
    }
};
