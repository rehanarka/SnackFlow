<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user')->cascadeOnDelete();
            $table->timestamp('tanggal_transaksi')->nullable();
            $table->string('status_transaksi')->default('draft');
            $table->string('status_pembayaran')->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->string('resi')->nullable();
            $table->unsignedBigInteger('ongkir')->default(0);
            $table->string('midtrans_order_id')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
