<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KatalogProduk;
use Illuminate\Support\Facades\Storage;

class KatalogController extends Controller
{
    public function tambahProduk(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],
        [
            'nama_produk.required' => 'Nama produk tidak boleh kosong.',
            'harga.required' => 'Harga produk tidak boleh kosong.',
            'harga.numeric' => 'Harga produk harus berupa angka.',
            'stok.required' => 'Stok produk tidak boleh kosong.',
            'stok.integer' => 'Stok produk harus berupa angka bulat.',
            'kategori.required' => 'Kategori produk tidak boleh kosong.',
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
        'kategori' => 'nullable|string|max:255',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
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
        return view('katalog.katalogUser', compact('produks'));
    }
}
