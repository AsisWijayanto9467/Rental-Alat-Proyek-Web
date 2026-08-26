<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaans')->cascadeOnDelete();
            $table->foreignId('pengembalian_id')->nullable()->constrained('pengembalians')->nullOnDelete();
            $table->enum('jenis_denda', ['terlambat', 'kerusakan', 'kehilangan']);
            $table->decimal('jumlah', 14, 2);
            $table->text('alasan');
            $table->enum('status', ['pending', 'dibayar', 'ditangguhkan'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dendas');
    }
};
