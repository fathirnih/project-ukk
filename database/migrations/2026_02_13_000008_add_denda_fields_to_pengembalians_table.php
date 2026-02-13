<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->unsignedInteger('hari_terlambat')->default(0)->after('status');
            $table->unsignedInteger('denda_per_hari')->default(1000)->after('hari_terlambat');
            $table->unsignedBigInteger('total_denda')->default(0)->after('denda_per_hari');
        });
    }

    public function down(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->dropColumn(['hari_terlambat', 'denda_per_hari', 'total_denda']);
        });
    }
};
