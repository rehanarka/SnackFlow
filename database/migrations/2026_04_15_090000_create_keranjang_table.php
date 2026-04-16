<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_produk')->constrained('katalog_produks')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_produk');
            $table->timestamps();

            $table->unique(['id_user', 'id_produk']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
