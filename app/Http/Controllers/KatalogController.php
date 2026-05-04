<?php

namespace App\Http\Controllers;

use App\Models\DetailKeranjang;
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
            'foto_produk' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],
        [
            'required'=> 'Data tidak sesuai.',
            'berat.integer' => 'Data tidak sesuai.',
            'berat.min' => 'Berat produk minimal 1 gram.',
            'foto_produk.required' => 'Harap tambahkan foto produk.',
            'foto_produk.image' => 'Foto produk harus berupa gambar.',
            'foto_produk.mimes' => 'Foto produk harus berupa file JPEG, PNG, JPG, atau GIF.',
            'foto_produk.max' => 'Foto produk tidak boleh lebih dari 2MB.',
        ]);

        $namaProdukSudahAda = KatalogProduk::whereRaw('LOWER(nama_produk) = ?', [strtolower($data['nama_produk'])])->exists();

        if ($namaProdukSudahAda) {
            return back()->withInput()->with('popup_peringatan', 'Produk dengan nama tersebut sudah tersedia. Silakan gunakan nama produk lain.');
        }

        if ($request->hasFile('foto_produk')) {
            $data['foto_produk'] = $request->file('foto_produk')->store('produk', 'public');
        }
        KatalogProduk::create($data);
        return back()->with('success', 'Data berhasil dibuat.');
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
        'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        'produk_id_edit' => 'nullable|integer',
    ], [
        'nama_produk.required' => 'Data tidak sesuai.',
        'nama_produk.max' => 'Data tidak sesuai.',
        'harga.required' => 'Data tidak sesuai.',
        'harga.numeric' => 'Data tidak sesuai.',
        'stok.required' => 'Data tidak sesuai.',
        'stok.integer' => 'Data tidak sesuai.',
        'berat.required' => 'Data tidak sesuai.',
        'berat.integer' => 'Data tidak sesuai.',
        'berat.min' => 'Berat produk minimal 1 gram.',
        'deskripsi.string' => 'Data tidak sesuai.',
        'foto_produk.image' => 'Data tidak sesuai.',
        'foto_produk.mimes' => 'Data tidak sesuai.',
        'foto_produk.max' => 'Foto produk tidak boleh lebih dari 2MB.',
    ]);

    unset($validated['produk_id_edit']);

    if ($request->hasFile('foto_produk')) {
        $validated['foto_produk'] = $request->file('foto_produk')->store('produk', 'public');
    }

    $produk->update($validated);

    return back()->with('update_success', 'Data berhasil diedit.');
}

    public function hapusProduk($id)
    {
        $produk = KatalogProduk::findOrFail($id);

        if ($produk->foto_produk) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return back()->with('delete_success', 'Data berhasil dihapus.');
    }
    public function viewKatalog()
    {
        $produks = KatalogProduk::orderByDesc('id')->get();
        return view('katalog.katalogAdmin', compact('produks'));
    }

    public function viewKatalogUser()
    {
        $produks = KatalogProduk::orderByDesc('id')->get();
        $keranjangItems = auth()->user()->keranjang()->with('produk')->orderByDesc('id')->get();
        $cartCount = $keranjangItems->sum('jumlah_produk');

        
        return view('katalog.katalogUser', compact('produks', 'keranjangItems', 'cartCount'));
    }

    public function tambahKeKeranjang(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:katalog_produk,id',
            'jumlah_produk' => 'required|integer|min:1',
            'redirect_to_checkout' => 'nullable',
        ], [
            'jumlah_produk.required' => 'Jumlah produk harus diisi.',
            'jumlah_produk.integer' => 'Jumlah produk harus berupa angka bulat.',
            'jumlah_produk.min' => 'Jumlah produk minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $produk = KatalogProduk::lockForUpdate()->findOrFail($validated['produk_id']);
                $keranjang = Keranjang::firstOrCreate([
                    'user_id' => auth()->id(),
                ]);

                $keranjangItem = DetailKeranjang::where('keranjang_id', $keranjang->id)
                    ->where('produk_id', $validated['produk_id'])
                    ->lockForUpdate()
                    ->first();

                if ($validated['jumlah_produk'] > $produk->stok) {
                    throw new RuntimeException('Jumlah produk di keranjang melebihi stok yang tersedia.');
                }

                $jumlahBaru = ($keranjangItem?->jumlah_produk ?? 0) + $validated['jumlah_produk'];

                DetailKeranjang::updateOrCreate(
                    ['keranjang_id' => $keranjang->id, 'produk_id' => $validated['produk_id']],
                    ['jumlah_produk' => $jumlahBaru]
                );

                $produk->decrement('stok', $validated['jumlah_produk']);
            });
        } catch (RuntimeException $exception) {
            return back()->withErrors([
                'keranjang' => $exception->getMessage(),
            ])->withInput();
        }

        if ($request->boolean('redirect_to_checkout')) {
            return redirect()->route('user.checkout')->with('success', 'Produk langsung masuk ke checkout sesuai jumlah yang dipilih.');
        }

        return back()->with('success', 'Produk berhasil dimasukkan ke keranjang.');
    }

    public function hapusDariKeranjang($id)
    {
        DB::transaction(function () use ($id) {
            $keranjang = Keranjang::where('user_id', auth()->id())->firstOrFail();
            $item = DetailKeranjang::where('keranjang_id', $keranjang->id)->lockForUpdate()->findOrFail($id);
            $produk = KatalogProduk::lockForUpdate()->find($item->produk_id);

            if ($produk) {
                $produk->increment('stok', $item->jumlah_produk);
            }

            $item->delete();

            if (!DetailKeranjang::where('keranjang_id', $keranjang->id)->exists()) {
                $keranjang->delete();
            }
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
                $keranjang = Keranjang::where('user_id', auth()->id())->firstOrFail();
                $item = DetailKeranjang::where('keranjang_id', $keranjang->id)
                    ->lockForUpdate()
                    ->findOrFail($id);
                $produk = KatalogProduk::lockForUpdate()->findOrFail($item->produk_id);
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
