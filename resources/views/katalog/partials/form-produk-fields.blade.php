@php
    $prefix = $prefix ?? 'produk';
    $namaProduk = $namaProduk ?? '';
    $harga = $harga ?? '';
    $stok = $stok ?? '';
    $berat = $berat ?? '';
    $deskripsi = $deskripsi ?? '';
@endphp

<div>
    <label for="{{ $prefix }}_nama_produk" class="block mb-1 text-sm font-medium text-gray-700">Nama Produk</label>
    <input type="text" id="{{ $prefix }}_nama_produk" name="nama_produk" value="{{ $namaProduk }}" autocomplete="off" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama produk" required>
</div>

<div>
    <label for="{{ $prefix }}_harga" class="block mb-1 text-sm font-medium text-gray-700">Harga</label>
    <input type="number" id="{{ $prefix }}_harga" name="harga" value="{{ $harga }}" autocomplete="off" class="[appearance:textfield] w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" placeholder="Masukkan harga" required>
</div>

<div>
    <label for="{{ $prefix }}_stok" class="block mb-1 text-sm font-medium text-gray-700">Stok</label>
    <input type="number" id="{{ $prefix }}_stok" name="stok" value="{{ $stok }}" autocomplete="off" class="[appearance:textfield] w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" placeholder="Masukkan stok" required>
</div>

<div>
    <label for="{{ $prefix }}_berat" class="block mb-1 text-sm font-medium text-gray-700">Berat (gram)</label>
    <input type="number" id="{{ $prefix }}_berat" name="berat" value="{{ $berat }}" autocomplete="off" class="[appearance:textfield] w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" placeholder="Masukkan berat produk" required>
</div>

<div>
    <label for="{{ $prefix }}_foto_produk" class="block mb-1 text-sm font-medium text-gray-700">Foto Produk</label>
    <input type="file" id="{{ $prefix }}_foto_produk" name="foto_produk" accept="image/*" autocomplete="off" class="w-full rounded-lg border border-gray-300 px-3 py-2 file:mr-3 file:rounded file:border-0 file:bg-blue-100 file:px-3 file:py-1 file:text-blue-700">
</div>

<div>
    <label for="{{ $prefix }}_deskripsi" class="block mb-1 text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea id="{{ $prefix }}_deskripsi" name="deskripsi" rows="3" autocomplete="off" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan deskripsi produk">{{ $deskripsi }}</textarea>
</div>
