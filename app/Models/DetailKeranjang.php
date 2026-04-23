<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKeranjang extends Model
{
    public $timestamps = false;

    protected $table = 'detail_keranjang';

    protected $fillable = [
        'keranjang_id',
        'produk_id',
        'jumlah_produk',
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'keranjang_id');
    }

    public function produk()
    {
        return $this->belongsTo(KatalogProduk::class, 'produk_id');
    }
}
