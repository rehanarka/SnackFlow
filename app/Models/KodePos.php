<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodePos extends Model
{
    public $timestamps = false;

    protected $table = 'kode_pos';

    protected $fillable = [
        'nomor_kode_pos',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'kode_pos_id');
    }

    public function penerima()
    {
        return $this->hasMany(Penerima::class, 'kode_pos_id');
    }
}
