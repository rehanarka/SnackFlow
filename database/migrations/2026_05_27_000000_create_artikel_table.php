<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 100);
            $table->string('gambar_artikel', 200)->nullable();
            $table->text('konten_artikel');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
