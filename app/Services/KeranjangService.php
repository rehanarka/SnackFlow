<?php

namespace App\Services;

use App\Models\DetailKeranjang;
use App\Models\Keranjang;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KeranjangService
{
    public function reconcileUserCart(User $user): Collection
    {
        return DB::transaction(function () use ($user) {
            $keranjang = Keranjang::where('user_id', $user->id)->first();

            if (!$keranjang) {
                return collect();
            }

            $items = DetailKeranjang::query()
                ->where('keranjang_id', $keranjang->id)
                ->with('produk')
                ->lockForUpdate()
                ->get();

            $removedProducts = collect();

            foreach ($items as $item) {
                $produk = $item->produk;

                if (!$produk || (int) $item->jumlah_produk > (int) $produk->stok) {
                    $removedProducts->push($produk?->nama_produk ?? 'Produk tidak tersedia');
                    $item->delete();
                }
            }

            if (!DetailKeranjang::where('keranjang_id', $keranjang->id)->exists()) {
                $keranjang->delete();
            }

            return $removedProducts->values();
        });
    }
}
