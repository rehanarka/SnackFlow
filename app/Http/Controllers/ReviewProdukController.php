<?php

namespace App\Http\Controllers;

use App\Models\KatalogProduk;
use App\Models\ReviewProduk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewProdukController extends Controller
{
    public function adminIndex(KatalogProduk $produk)
    {
        $produk->load(['reviewProduk.user', 'reviewProduk.transaksi']);
        $isAdminView = true;
        $backRoute = route('admin.katalog');

        return view('review.HalamanReviewProdukAdmin', compact('produk', 'isAdminView', 'backRoute'));
    }

    public function userProductIndex(KatalogProduk $produk)
    {
        $produk->load(['reviewProduk.user', 'reviewProduk.transaksi']);
        $isAdminView = false;
        $backRoute = route('user.katalog');

        return view('review.HalamanReviewProdukAdmin', compact('produk', 'isAdminView', 'backRoute'));
    }

    public function create(Request $request, Transaksi $transaksi)
    {
        abort_unless($transaksi->user_id === $request->user()->id, 404);

        if ($transaksi->status_transaksi !== 'Selesai') {
            return redirect()->route('user.transaksi')->with('review_error', 'Data Tidak Sesuai');
        }

        $transaksi->load([
            'detailTransaksi.produk',
            'reviewProduk' => fn ($query) => $query->where('user_id', $request->user()->id)->with('produk'),
        ]);

        return view('review.FormReviewProduk', compact('transaksi'));
    }

    public function store(Request $request, Transaksi $transaksi)
    {
        abort_unless($transaksi->user_id === $request->user()->id, 404);

        if ($transaksi->status_transaksi !== 'Selesai') {
            return back()->with('review_error', 'Data Tidak Sesuai');
        }

        if (!$request->filled('produk_id') || !$request->filled('rating') || !$request->filled('review_produk')) {
            return back()->withInput()->with('review_error', 'Data Tidak Boleh Kosong');
        }

        $validator = Validator::make($request->all(), [
            'produk_id' => 'required|integer|exists:katalog_produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_produk' => 'required|string',
            'foto_review' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('review_error', 'Data Tidak Sesuai');
        }

        $validated = $validator->validated();
        $produkAdaDiTransaksi = $transaksi->detailTransaksi()
            ->where('produk_id', $validated['produk_id'])
            ->exists();

        if (!$produkAdaDiTransaksi) {
            return back()->withInput()->with('review_error', 'Data Tidak Sesuai');
        }

        $review = ReviewProduk::firstOrNew([
            'user_id' => $request->user()->id,
            'transaksi_id' => $transaksi->id,
            'produk_id' => $validated['produk_id'],
        ]);

        $data = [
            'user_id' => $request->user()->id,
            'transaksi_id' => $transaksi->id,
            'produk_id' => $validated['produk_id'],
            'rating' => (int) $validated['rating'],
            'review_produk' => $validated['review_produk'],
        ];

        if ($request->hasFile('foto_review')) {
            $data['foto_review'] = $request->file('foto_review')->store('review', 'public');
        }

        $review->fill($data);
        $review->save();

        return redirect()
            ->route('user.transaksi.review', $transaksi)
            ->with('review_success', 'Reiew Produk Berhasil Dilakukan');
    }

    public function destroy(Request $request, Transaksi $transaksi, ReviewProduk $review)
    {
        abort_unless($transaksi->user_id === $request->user()->id, 404);
        abort_unless($review->user_id === $request->user()->id && $review->transaksi_id === $transaksi->id, 404);

        if ($review->foto_review) {
            Storage::disk('public')->delete($review->foto_review);
        }

        $review->delete();

        return redirect()
            ->route('user.transaksi.review', $transaksi)
            ->with('review_delete_success', 'Review berhasil dihapus');
    }
}
