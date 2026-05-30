<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::orderByDesc('id')->get();
        $isAdminView = auth()->user()?->role === 'admin';

        return view('artikel.HalamanArtikel', compact('artikels', 'isAdminView'));
    }

    public function create()
    {
        return view('artikel.FormArtikel', [
            'artikel' => null,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->filled('judul') || !$request->filled('konten_artikel')) {
            return back()->withInput()->with('artikel_error', 'Data Tidak Boleh Kosong');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:100',
            'gambar_artikel' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'konten_artikel' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('artikel_error', 'Data Tidak Sesuai');
        }

        $data = $validator->validated();

        if ($request->hasFile('gambar_artikel')) {
            $data['gambar_artikel'] = $request->file('gambar_artikel')->store('artikel', 'public');
        }

        Artikel::create($data);

        return redirect()->route('admin.artikel')->with('artikel_success', 'Artikel Berhasil Dibuat');
    }

    public function show(Artikel $artikel)
    {
        $isAdminView = auth()->user()?->role === 'admin';

        return view('artikel.DetailArtikel', compact('artikel', 'isAdminView'));
    }

    public function edit(Artikel $artikel)
    {
        return view('artikel.FormArtikel', [
            'artikel' => $artikel,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Artikel $artikel)
    {
        if (!$request->filled('judul') || !$request->filled('konten_artikel')) {
            return back()->withInput()->with('artikel_error', 'Data Tidak Boleh Kosong');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:100',
            'gambar_artikel' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'konten_artikel' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('artikel_error', 'Data Tidak Sesuai');
        }

        $data = $validator->validated();

        if ($request->hasFile('gambar_artikel')) {
            if ($artikel->gambar_artikel) {
                Storage::disk('public')->delete($artikel->gambar_artikel);
            }

            $data['gambar_artikel'] = $request->file('gambar_artikel')->store('artikel', 'public');
        } else {
            unset($data['gambar_artikel']);
        }

        $artikel->update($data);

        return redirect()->route('admin.artikel.show', $artikel)->with('artikel_success', 'Artikel berhasil diubah');
    }

    public function destroy(Artikel $artikel)
    {
        if ($artikel->gambar_artikel) {
            Storage::disk('public')->delete($artikel->gambar_artikel);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel')->with('artikel_success', 'artikel berhasil dihapus');
    }
}
