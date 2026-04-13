<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatalogProduk extends Model
{
    protected $fillable = [
        'nama_produk',
        'kategori',
        'harga',
        'stok',
        'deskripsi',
        'foto',
    ];
}
