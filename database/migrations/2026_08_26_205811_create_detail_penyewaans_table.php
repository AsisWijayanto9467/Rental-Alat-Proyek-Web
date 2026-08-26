<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penyewaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaans')->cascadeOnDelete();
            $table->foreignId('alat_id')->constrained('alat_proyeks')->cascadeOnDelete();
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_sewa', 12, 2);
            $table->decimal('subtotal', 14, 2);
            $table->string('kondisi_sebelum')->nullable();
            $table->string('kondisi_sesudah')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penyewaans');
    }
};
