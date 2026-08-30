<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('kondisi_alat');
        });

        // Laravel's SQLite grammar creates `enum` columns as unconstrained VARCHAR,
        // so no ALTER is needed there. Emit the MySQL-specific ALTER only on MySQL.
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pengembalians MODIFY status ENUM('menunggu_inspeksi', 'diterima', 'perlu_perbaikan', 'ditolak') NOT NULL DEFAULT 'menunggu_inspeksi'");
        }
    }

    public function down(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pengembalians MODIFY status ENUM('diterima', 'perlu_perbaikan', 'ditolak') NOT NULL DEFAULT 'diterima'");
        }
    }
};
