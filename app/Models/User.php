<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    public $timestamps = false;

    protected $table = 'user';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'avatar',
        'kode_pos_id',
        'no_telepon',
        'role',
        'otp',
        'otp_expired_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'otp_expired_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function keranjang()
    {
        return $this->hasManyThrough(
            DetailKeranjang::class,
            Keranjang::class,
            'user_id',
            'keranjang_id',
            'id',
            'id'
        );
    }

    public function keranjangUtama()
    {
        return $this->hasOne(Keranjang::class, 'user_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }

    public function kodePos()
    {
        return $this->belongsTo(KodePos::class, 'kode_pos_id');
    }
}
