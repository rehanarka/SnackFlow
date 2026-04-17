<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('estimasi_pengiriman');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_transaction_status')->nullable()->after('midtrans_transaction_id');
            $table->string('midtrans_fraud_status')->nullable()->after('midtrans_transaction_status');
            $table->string('payment_type')->nullable()->after('midtrans_fraud_status');
            $table->string('payment_code')->nullable()->after('payment_type');
            $table->text('snap_token')->nullable()->after('payment_code');
            $table->text('snap_redirect_url')->nullable()->after('snap_token');
            $table->timestamp('paid_at')->nullable()->after('snap_redirect_url');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_transaction_status',
                'midtrans_fraud_status',
                'payment_type',
                'payment_code',
                'snap_token',
                'snap_redirect_url',
                'paid_at',
            ]);
        });
    }
};
