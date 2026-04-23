<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('katalog_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('kategori')->nullable();
            $table->integer('harga');
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('foto_produk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalog_produk');
    }
};
