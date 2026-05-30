<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    public $timestamps = false;

    protected $table = 'artikel';

    protected $fillable = [
        'judul',
        'gambar_artikel',
        'konten_artikel',
    ];
}
