<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    public $timestamps = false;

    protected $table = 'kecamatan';

    protected $fillable = [
        'kabupaten_id',
        'kode_pos_id',
        'nama_kecamatan',
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function kodePos()
    {
        return $this->belongsTo(KodePos::class, 'kode_pos_id');
    }
}
