<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;
use RuntimeException;

class MidtransService
{
    public function __construct()
    {
        $this->clearBrokenProxyEnvironment();

        $serverKey = config('services.midtrans.server_key');
        $clientKey = config('services.midtrans.client_key');

        if (!$serverKey || !$clientKey) {
            throw new RuntimeException('Konfigurasi Midtrans belum lengkap di file .env.');
        }

        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    private function clearBrokenProxyEnvironment(): void
    {
        foreach (['HTTP_PROXY', 'HTTPS_PROXY', 'ALL_PROXY', 'http_proxy', 'https_proxy', 'all_proxy'] as $proxyKey) {
            if (getenv($proxyKey)) {
                putenv($proxyKey);
            }

            unset($_ENV[$proxyKey], $_SERVER[$proxyKey]);
        }
    }

    public function getSnapScriptUrl(): string
    {
        return config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function createOrGetSnapTransaction(Transaksi $transaksi): array
    {
        if ($transaksi->snap_token && $transaksi->snap_redirect_url) {
            return [
                'token' => $transaksi->snap_token,
                'redirect_url' => $transaksi->snap_redirect_url,
                'order_id' => $transaksi->midtrans_order_id,
            ];
        }

        $orderId = $transaksi->midtrans_order_id ?: 'SNACKFLOW-' . $transaksi->id;

        $itemDetails = $transaksi->detailTransaksi->map(function ($detail) {
            return [
                'id' => 'PRODUK-' . $detail->id_produk,
                'price' => (int) $detail->harga_produk,
                'quantity' => (int) $detail->jumlah_produk,
                'name' => substr($detail->produk->nama_produk ?? 'Produk', 0, 50),
            ];
        })->values()->all();

        $itemDetails[] = [
            'id' => 'ONGKIR',
            'price' => (int) $transaksi->ongkir,
            'quantity' => 1,
            'name' => 'Biaya Pengiriman',
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $transaksi->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $transaksi->nama_penerima,
                'email' => $transaksi->user->email ?? 'customer@snackflow.test',
                'phone' => $transaksi->no_telp_penerima,
                'shipping_address' => [
                    'first_name' => $transaksi->nama_penerima,
                    'phone' => $transaksi->no_telp_penerima,
                    'address' => $transaksi->alamat_penerima,
                    'postal_code' => $transaksi->kode_pos_penerima,
                ],
            ],
            'item_details' => $itemDetails,
        ];

        $transaction = Snap::createTransaction($params);

        $transaksi->update([
            'midtrans_order_id' => $orderId,
            'snap_token' => $transaction->token ?? null,
            'snap_redirect_url' => $transaction->redirect_url ?? null,
        ]);

        return [
            'token' => $transaksi->snap_token,
            'redirect_url' => $transaksi->snap_redirect_url,
            'order_id' => $orderId,
        ];
    }

    public function handleNotification(): ?Transaksi
    {
        $notification = new Notification();
        $orderId = $notification->order_id ?? null;

        Log::info('Midtrans notification received', [
            'order_id' => $orderId,
            'transaction_status' => $notification->transaction_status ?? null,
            'payment_type' => $notification->payment_type ?? null,
        ]);

        if (!$orderId) {
            return null;
        }

        $transaksi = Transaksi::where('midtrans_order_id', $orderId)->first();

        if (!$transaksi) {
            return null;
        }

        return $this->applyTransactionStatus($transaksi, [
            'transaction_status' => $notification->transaction_status ?? null,
            'fraud_status' => $notification->fraud_status ?? null,
            'payment_type' => $notification->payment_type ?? null,
            'transaction_id' => $notification->transaction_id ?? null,
            'permata_va_number' => $notification->permata_va_number ?? null,
            'bill_key' => $notification->bill_key ?? null,
            'va_numbers' => $notification->va_numbers ?? [],
        ]);
    }

    public function syncTransactionStatus(Transaksi $transaksi): Transaksi
    {
        if (!$transaksi->midtrans_order_id) {
            throw new RuntimeException('Order Midtrans belum tersedia untuk transaksi ini.');
        }

        $status = Transaction::status($transaksi->midtrans_order_id);

        return $this->applyTransactionStatus($transaksi, [
            'transaction_status' => $status->transaction_status ?? null,
            'fraud_status' => $status->fraud_status ?? null,
            'payment_type' => $status->payment_type ?? null,
            'transaction_id' => $status->transaction_id ?? null,
            'permata_va_number' => $status->permata_va_number ?? null,
            'bill_key' => $status->bill_key ?? null,
            'va_numbers' => $status->va_numbers ?? [],
        ]);
    }

    private function applyTransactionStatus(Transaksi $transaksi, array $payload): Transaksi
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $vaNumbers = $payload['va_numbers'] ?? [];
        $paymentCode = ($payload['permata_va_number'] ?? null)
            ?? ($payload['bill_key'] ?? null)
            ?? (($vaNumbers[0]->va_number ?? null))
            ?? null;

        $statusPembayaran = 'pending';
        $statusPesanan = 'Menunggu Pembayaran';
        $paidAt = null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $statusPembayaran = 'challenge';
                $statusPesanan = 'Menunggu Verifikasi';
            } else {
                $statusPembayaran = 'paid';
                $statusPesanan = 'Diproses';
                $paidAt = now();
            }
        } elseif ($transactionStatus === 'settlement') {
            $statusPembayaran = 'paid';
            $statusPesanan = 'Diproses';
            $paidAt = now();
        } elseif ($transactionStatus === 'pending') {
            $statusPembayaran = 'pending';
            $statusPesanan = 'Menunggu Pembayaran';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $statusPembayaran = 'dibatalkan';
            $statusPesanan = 'Dibatalkan';
        }

        $transaksi->update([
            'midtrans_transaction_id' => $transactionId,
            'midtrans_transaction_status' => $transactionStatus,
            'midtrans_fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
            'payment_code' => $paymentCode,
            'status_pembayaran' => $statusPembayaran,
            'status_pesanan' => $statusPesanan,
            'alasan_penolakan' => $statusPesanan === 'Dibatalkan' ? ($transaksi->alasan_penolakan ?: 'Pembayaran dibatalkan atau kedaluwarsa.') : null,
            'paid_at' => $paidAt ?: $transaksi->paid_at,
        ]);

        return $transaksi->fresh();
    }
}
