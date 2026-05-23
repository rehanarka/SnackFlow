<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewProduk extends Model
{
    public $timestamps = false;

    protected $table = 'review_produk';

    protected $fillable = [
        'user_id',
        'transaksi_id',
        'produk_id',
        'rating',
        'review_produk',
        'foto_review',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function produk()
    {
        return $this->belongsTo(KatalogProduk::class, 'produk_id');
    }
}
