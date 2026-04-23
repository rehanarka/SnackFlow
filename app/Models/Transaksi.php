<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    public $timestamps = false;

    protected $table = 'transaksi';

    protected $fillable = [
        'user_id',
        'penerima_id',
        'metode_pembayaran_id',
        'tanggal_transaksi',
        'status_transaksi',
        'catatan_admin',
        'resi',
        'ongkir',
        'midtrans_order_id',
        'snap_token',
        'snap_redirect_url',
        'status_pembayaran',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function penerima()
    {
        return $this->belongsTo(Penerima::class, 'penerima_id');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }

    public function getNamaPenerimaAttribute(): ?string
    {
        return $this->penerima?->nama_penerima;
    }

    public function getNoTelpPenerimaAttribute(): ?string
    {
        return $this->penerima?->no_telp_penerima;
    }

    public function getAlamatPenerimaAttribute(): ?string
    {
        return $this->penerima?->detail_alamat;
    }

    public function getKodePosPenerimaAttribute(): ?string
    {
        return $this->penerima?->kodePos?->nomor_kode_pos;
    }

    public function getSubtotalAttribute(): int
    {
        return (int) $this->detailTransaksi->sum('subtotal_produk');
    }

    public function getTotalBayarAttribute(): int
    {
        return $this->subtotal + (int) $this->ongkir;
    }

    public function getStatusPesananAttribute(): ?string
    {
        return $this->attributes['status_transaksi'] ?? null;
    }

    public function setStatusPesananAttribute($value): void
    {
        $this->attributes['status_transaksi'] = $value;
    }

    public function getAlasanPenolakanAttribute(): ?string
    {
        return $this->attributes['catatan_admin'] ?? null;
    }

    public function setAlasanPenolakanAttribute($value): void
    {
        $this->attributes['catatan_admin'] = $value;
    }

    public function getMetodePembayaranAttribute(): ?string
    {
        return $this->metodePembayaranRelation?->nama_metode_pembayaran;
    }

    protected function getMetodePembayaranRelationAttribute()
    {
        return $this->relationLoaded('metodePembayaran')
            ? $this->getRelation('metodePembayaran')
            : $this->metodePembayaran()->first();
    }
}
