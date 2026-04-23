<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    public $timestamps = false;

    protected $table = 'provinsi';

    protected $fillable = [
        'nama_provinsi',
    ];

    public function kabupaten()
    {
        return $this->hasMany(Kabupaten::class, 'provinsi_id');
    }
}
