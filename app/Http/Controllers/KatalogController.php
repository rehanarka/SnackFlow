<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KatalogProduk;

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
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }
        KatalogProduk::create($data);
        return back()->with('success', 'Produk berhasil ditambahkan.');
    }
    public function viewKatalog()
    {
        $produks = KatalogProduk::latest()->get();
        return view('katalog.katalogAdmin', compact('produks'));
    }
}
