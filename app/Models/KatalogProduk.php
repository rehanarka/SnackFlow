<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatalogProduk extends Model
{
    public $timestamps = false;

    protected $table = 'katalog_produk';

    protected $fillable = [
        'nama_produk',
        'kategori',
        'harga',
        'stok',
        'berat',
        'deskripsi',
        'foto_produk',
    ];

    public function keranjang()
    {
        return $this->hasMany(DetailKeranjang::class, 'produk_id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }

    public function reviewProduk()
    {
        return $this->hasMany(ReviewProduk::class, 'produk_id');
    }
}
