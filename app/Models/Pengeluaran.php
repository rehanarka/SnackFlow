<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    public $timestamps = false;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'nama_pengeluaran',
        'tanggal_pengeluaran',
        'nominal',
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'date',
    ];
}
