<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('nama_penerima');
            $table->string('no_telp_penerima');
            $table->text('alamat_penerima');
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('ongkir')->default(0);
            $table->unsignedBigInteger('total_bayar')->default(0);
            $table->string('kurir')->nullable();
            $table->string('service_pengiriman')->nullable();
            $table->string('estimasi_pengiriman')->nullable();
            $table->string('status_pesanan')->default('draft');
            $table->string('status_pembayaran')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
