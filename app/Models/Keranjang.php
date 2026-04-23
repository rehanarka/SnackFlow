<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    public $timestamps = false;

    protected $table = 'keranjang';

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailKeranjang()
    {
        return $this->hasMany(DetailKeranjang::class, 'keranjang_id');
    }
}
