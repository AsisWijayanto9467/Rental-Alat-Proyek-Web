<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->foreignId('denda_id')->nullable()->after('penyewaan_id')->constrained('dendas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('denda_id');
        });
    }
};
