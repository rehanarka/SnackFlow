<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
                'id' => 'PRODUK-' . $detail->produk_id,
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
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'day',
                'duration' => 1,
            ],
            'enabled_payments' => ['gopay'],
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
            'metode_pembayaran' => $notification->payment_type ?? null,
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
            'metode_pembayaran' => $notification->payment_type ?? null,
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
            'metode_pembayaran' => $status->payment_type ?? null,
            'transaction_id' => $status->transaction_id ?? null,
            'permata_va_number' => $status->permata_va_number ?? null,
            'bill_key' => $status->bill_key ?? null,
            'va_numbers' => $status->va_numbers ?? [],
        ]);
    }

    private function generateUniqueResi(): string
    {
        do {
            $resi = 'JNE-' . strtoupper(Str::random(10));
        } while (Transaksi::where('resi', $resi)->exists());

        return $resi;
    }

    private function applyTransactionStatus(Transaksi $transaksi, array $payload): Transaksi
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $metodePembayaran = $payload['metode_pembayaran'] ?? null;

        $statusPembayaran = 'pending';
        $statusPesanan = 'Dikonfirmasi';
        $resi = $transaksi->resi;

        if ($transactionStatus === 'capture') {
            $statusPembayaran = 'paid';
            $statusPesanan = 'Diproses';
            $resi ??= $this->generateUniqueResi();
        } elseif ($transactionStatus === 'settlement') {
            $statusPembayaran = 'paid';
            $statusPesanan = 'Diproses';
            $resi ??= $this->generateUniqueResi();
        } elseif ($transactionStatus === 'pending') {
            $statusPembayaran = 'pending';
            $statusPesanan = 'Dikonfirmasi';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $statusPembayaran = 'dibatalkan';
            $statusPesanan = 'Dibatalkan';
        }

        $transaksi->update([
            'metode_pembayaran' => $metodePembayaran ?? $transaksi->metode_pembayaran,
            'status_pembayaran' => $statusPembayaran,
            'status_transaksi' => $statusPesanan,
            'resi' => $resi,
            'catatan_admin' => $statusPesanan === 'Dibatalkan' ? ($transaksi->catatan_admin ?: 'Pembayaran dibatalkan atau kedaluwarsa.') : null,
        ]);

        return $transaksi->fresh();
    }
}
