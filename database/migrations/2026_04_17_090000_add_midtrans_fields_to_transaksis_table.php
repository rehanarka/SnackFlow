<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->text('snap_token')->nullable()->after('midtrans_order_id');
            $table->text('snap_redirect_url')->nullable()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'snap_redirect_url',
            ]);
        });
    }
};
