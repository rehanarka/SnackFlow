<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengeluaran');
            $table->date('tanggal_pengeluaran');
            $table->unsignedBigInteger('nominal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
