<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('katalog_produk', function (Blueprint $table) {
            $table->unsignedInteger('berat')->default(0)->after('stok');
        });
    }

    public function down(): void
    {
        Schema::table('katalog_produk', function (Blueprint $table) {
            $table->dropColumn('berat');
        });
    }
};
