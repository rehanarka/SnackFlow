<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerima extends Model
{
    public $timestamps = false;

    protected $table = 'penerima';

    protected $fillable = [
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kode_pos_id',
        'nama_penerima',
        'no_telp_penerima',
        'detail_alamat',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kodePos()
    {
        return $this->belongsTo(KodePos::class, 'kode_pos_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'penerima_id');
    }
}
