<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('transaksi', 'metode_pembayaran')) {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->string('metode_pembayaran')->nullable()->after('tanggal_transaksi');
            });
        }

        if (Schema::hasTable('metode_pembayaran') && Schema::hasColumn('transaksi', 'metode_pembayaran_id')) {
            DB::statement('
                UPDATE transaksi t
                LEFT JOIN metode_pembayaran mp ON mp.id = t.metode_pembayaran_id
                SET t.metode_pembayaran = COALESCE(t.metode_pembayaran, mp.nama_metode_pembayaran)
            ');
        }

        if (Schema::hasColumn('transaksi', 'metode_pembayaran_id')) {
            try {
                Schema::table('transaksi', function (Blueprint $table) {
                    $table->dropForeign(['metode_pembayaran_id']);
                });
            } catch (Throwable) {
                // ignore missing foreign constraint
            }

            Schema::table('transaksi', function (Blueprint $table) {
                $table->dropColumn('metode_pembayaran_id');
            });
        }

        Schema::dropIfExists('metode_pembayaran');
    }

    public function down(): void
    {
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_metode_pembayaran')->unique();
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreignId('metode_pembayaran_id')
                ->nullable()
                ->after('penerima_id')
                ->constrained('metode_pembayaran')
                ->nullOnDelete();
        });

        DB::statement('
            INSERT INTO metode_pembayaran (nama_metode_pembayaran)
            SELECT DISTINCT metode_pembayaran
            FROM transaksi
            WHERE metode_pembayaran IS NOT NULL AND metode_pembayaran <> ""
        ');

        DB::statement('
            UPDATE transaksi t
            LEFT JOIN metode_pembayaran mp ON mp.nama_metode_pembayaran = t.metode_pembayaran
            SET t.metode_pembayaran_id = mp.id
        ');
    }
};
