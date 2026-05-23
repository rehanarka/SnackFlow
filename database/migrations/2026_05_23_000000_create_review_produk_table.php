<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user')->cascadeOnDelete();
            $table->foreignId('transaksi_id')->constrained('transaksi')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('katalog_produk')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('review_produk');
            $table->string('foto_review', 200)->nullable();
            $table->unique(['user_id', 'transaksi_id', 'produk_id'], 'review_produk_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_produk');
    }
};
