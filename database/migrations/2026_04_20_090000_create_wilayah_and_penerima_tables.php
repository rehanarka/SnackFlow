<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kode_pos', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kode_pos', 10)->unique();
        });

        Schema::create('provinsi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_provinsi');
        });

        Schema::create('kabupaten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')->constrained('provinsi')->cascadeOnDelete();
            $table->string('nama_kabupaten');
        });

        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabupaten_id')->constrained('kabupaten')->cascadeOnDelete();
            $table->foreignId('kode_pos_id')->nullable()->constrained('kode_pos')->nullOnDelete();
            $table->string('nama_kecamatan');
        });

        Schema::create('penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')->nullable()->constrained('provinsi')->nullOnDelete();
            $table->foreignId('kabupaten_id')->nullable()->constrained('kabupaten')->nullOnDelete();
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatan')->nullOnDelete();
            $table->foreignId('kode_pos_id')->nullable()->constrained('kode_pos')->nullOnDelete();
            $table->string('nama_penerima');
            $table->string('no_telp_penerima')->nullable();
            $table->text('detail_alamat');
        });

        Schema::table('user', function (Blueprint $table) {
            $table->foreignId('kode_pos_id')->nullable()->after('avatar')->constrained('kode_pos')->nullOnDelete();
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreignId('penerima_id')->nullable()->after('user_id')->constrained('penerima')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropConstrainedForeignId('penerima_id');
        });

        Schema::table('user', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kode_pos_id');
        });

        Schema::dropIfExists('penerima');
        Schema::dropIfExists('kecamatan');
        Schema::dropIfExists('kabupaten');
        Schema::dropIfExists('provinsi');
        Schema::dropIfExists('kode_pos');
    }
};
