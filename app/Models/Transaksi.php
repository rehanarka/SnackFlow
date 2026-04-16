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
        'status_pesanan',
        'status_pembayaran',
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
