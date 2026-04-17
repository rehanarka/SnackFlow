<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'id_user',
        'nama_penerima',
        'no_telp_penerima',
        'alamat_penerima',
        'kode_pos_penerima',
        'rajaongkir_destination_id',
        'rajaongkir_destination_label',
        'subtotal',
        'ongkir',
        'total_bayar',
        'kurir',
        'service_pengiriman',
        'estimasi_pengiriman',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_transaction_status',
        'midtrans_fraud_status',
        'payment_type',
        'payment_code',
        'snap_token',
        'snap_redirect_url',
        'paid_at',
        'status_pesanan',
        'status_pembayaran',
        'alasan_penolakan',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
