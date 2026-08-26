<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat_proyeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->constrained('lokasis')->cascadeOnDelete();
            $table->string('kode_alat')->unique();
            $table->string('nama_alat');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_sewa_harian', 12, 2);
            $table->integer('stok')->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->enum('status', ['tersedia', 'disewa', 'maintenance', 'tidak_aktif'])->default('tersedia');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat_proyeks');
    }
};
