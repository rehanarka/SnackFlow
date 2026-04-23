<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    public $timestamps = false;

    protected $table = 'metode_pembayaran';

    protected $fillable = [
        'nama_metode_pembayaran',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'metode_pembayaran_id');
    }
}
