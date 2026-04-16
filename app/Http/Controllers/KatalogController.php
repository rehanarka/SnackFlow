<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\KatalogProduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class KatalogController extends Controller
{
    public function tambahProduk(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'berat' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],
        [
            'nama_produk.required' => 'Nama produk tidak boleh kosong.',
            'harga.required' => 'Harga produk tidak boleh kosong.',
            'harga.numeric' => 'Harga produk harus berupa angka.',
            'stok.required' => 'Stok produk tidak boleh kosong.',
            'stok.integer' => 'Stok produk harus berupa angka bulat.',
            'berat.required' => 'Berat produk tidak boleh kosong.',
            'berat.integer' => 'Berat produk harus berupa angka bulat.',
            'berat.min' => 'Berat produk minimal 1 gram.',
            'foto.required' => 'Harap tambahkan foto produk.',
            'foto.image' => 'Foto produk harus berupa gambar.',
            'foto.mimes' => 'Foto produk harus berupa file JPEG, PNG, JPG, atau GIF.',
            'foto.max' => 'Foto produk tidak boleh lebih dari 2MB.',
        ]);

        $namaProdukSudahAda = KatalogProduk::whereRaw('LOWER(nama_produk) = ?', [strtolower($data['nama_produk'])])->exists();

        if ($namaProdukSudahAda) {
            return back()->withInput()->with('popup_peringatan', 'Produk dengan nama tersebut sudah tersedia. Silakan gunakan nama produk lain.');
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }
        KatalogProduk::create($data);
        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

 public function updateProduk(Request $request, $id)
{
    $produk = KatalogProduk::findOrFail($id);

    $validated = $request->validate([
        'nama_produk' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'berat' => 'required|integer|min:1',
        'deskripsi' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
    ]);

    if ($request->hasFile('foto')) {
        $validated['foto'] = $request->file('foto')->store('produk', 'public');
    }

    $produk->update($validated);

    return back()->with('success', 'Produk berhasil diupdate');
}

    public function hapusProduk($id)
    {
        $produk = KatalogProduk::findOrFail($id);

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
    public function viewKatalog()
    {
        $produks = KatalogProduk::latest()->get();
        return view('katalog.katalogAdmin', compact('produks'));
    }

    public function viewKatalogUser()
    {
        $produks = KatalogProduk::latest()->get();
        $keranjangItems = auth()->user()->keranjang()->with('produk')->latest()->get();
        $cartCount = $keranjangItems->sum('jumlah_produk');

        return view('katalog.katalogUser', compact('produks', 'keranjangItems', 'cartCount'));
    }

    public function tambahKeKeranjang(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:katalog_produks,id',
            'jumlah_produk' => 'required|integer|min:1',
        ], [
            'jumlah_produk.required' => 'Jumlah produk harus diisi.',
            'jumlah_produk.integer' => 'Jumlah produk harus berupa angka bulat.',
            'jumlah_produk.min' => 'Jumlah produk minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $produk = KatalogProduk::lockForUpdate()->findOrFail($validated['id_produk']);
                $keranjangItem = Keranjang::where('id_user', auth()->id())
                    ->where('id_produk', $validated['id_produk'])
                    ->lockForUpdate()
                    ->first();

                if ($validated['jumlah_produk'] > $produk->stok) {
                    throw new RuntimeException('Jumlah produk di keranjang melebihi stok yang tersedia.');
                }

                $jumlahBaru = ($keranjangItem?->jumlah_produk ?? 0) + $validated['jumlah_produk'];

                Keranjang::updateOrCreate(
                    ['id_user' => auth()->id(), 'id_produk' => $validated['id_produk']],
                    ['jumlah_produk' => $jumlahBaru]
                );

                $produk->decrement('stok', $validated['jumlah_produk']);
            });
        } catch (RuntimeException $exception) {
            return back()->withErrors([
                'keranjang' => $exception->getMessage(),
            ])->withInput();
        }

        return back()->with('success', 'Produk berhasil dimasukkan ke keranjang.');
    }

    public function hapusDariKeranjang($id)
    {
        DB::transaction(function () use ($id) {
            $item = Keranjang::where('id_user', auth()->id())->lockForUpdate()->findOrFail($id);
            $produk = KatalogProduk::lockForUpdate()->find($item->id_produk);

            if ($produk) {
                $produk->increment('stok', $item->jumlah_produk);
            }

            $item->delete();
        });

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function updateJumlahKeranjang(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah_produk' => 'required|integer|min:1',
        ], [
            'jumlah_produk.required' => 'Jumlah produk harus diisi.',
            'jumlah_produk.integer' => 'Jumlah produk harus berupa angka bulat.',
            'jumlah_produk.min' => 'Jumlah produk minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($id, $validated) {
                $item = Keranjang::where('id_user', auth()->id())
                    ->lockForUpdate()
                    ->findOrFail($id);
                $produk = KatalogProduk::lockForUpdate()->findOrFail($item->id_produk);
                $jumlahLama = (int) $item->jumlah_produk;
                $jumlahBaru = (int) $validated['jumlah_produk'];
                $selisih = $jumlahBaru - $jumlahLama;

                if ($selisih > 0) {
                    if ($selisih > $produk->stok) {
                        throw new RuntimeException('Jumlah produk di keranjang melebihi stok yang tersedia.');
                    }

                    $produk->decrement('stok', $selisih);
                } elseif ($selisih < 0) {
                    $produk->increment('stok', abs($selisih));
                }

                $item->update([
                    'jumlah_produk' => $jumlahBaru,
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->withErrors([
                'keranjang' => $exception->getMessage(),
            ]);
        }

        return back()->with('success', 'Jumlah produk di keranjang berhasil diperbarui.');
    }
}
