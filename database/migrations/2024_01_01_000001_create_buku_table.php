<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('isbn')->nullable();
            $table->string('pengarang');
            $table->string('penerbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->integer('jumlah')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
