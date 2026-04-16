<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $fillable = [
        'id_transaksi',
        'id_produk',
        'jumlah_produk',
        'harga_produk',
        'subtotal_produk',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function produk()
    {
        return $this->belongsTo(KatalogProduk::class, 'id_produk');
    }
}
