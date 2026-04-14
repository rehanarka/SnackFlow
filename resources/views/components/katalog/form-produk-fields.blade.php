@props([
    'prefix',
    'namaProduk' => '',
    'kategori' => '',
    'harga' => '',
    'stok' => '',
    'deskripsi' => '',
])

<div>
    <label for="{{ $prefix }}_nama_produk" class="block mb-1 text-sm font-medium text-gray-700">Nama Produk</label>
    <input type="text" id="{{ $prefix }}_nama_produk" name="nama_produk" value="{{ $namaProduk }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama produk" required>
</div>

<div>
    <label for="{{ $prefix }}_kategori" class="block mb-1 text-sm font-medium text-gray-700">Kategori</label>
    <input type="text" id="{{ $prefix }}_kategori" name="kategori" value="{{ $kategori }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan kategori">
</div>

<div>
    <label for="{{ $prefix }}_harga" class="block mb-1 text-sm font-medium text-gray-700">Harga</label>
    <input type="number" id="{{ $prefix }}_harga" name="harga" value="{{ $harga }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan harga" required>
</div>

<div>
    <label for="{{ $prefix }}_stok" class="block mb-1 text-sm font-medium text-gray-700">Stok</label>
    <input type="number" id="{{ $prefix }}_stok" name="stok" value="{{ $stok }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan stok" required>
</div>

<div>
    <label for="{{ $prefix }}_foto" class="block mb-1 text-sm font-medium text-gray-700">Foto Produk</label>
    <input type="file" id="{{ $prefix }}_foto" name="foto" accept="image/*" class="w-full rounded-lg border border-gray-300 px-3 py-2 file:mr-3 file:rounded file:border-0 file:bg-blue-100 file:px-3 file:py-1 file:text-blue-700">
</div>

<div>
    <label for="{{ $prefix }}_deskripsi" class="block mb-1 text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea id="{{ $prefix }}_deskripsi" name="deskripsi" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan deskripsi produk">{{ $deskripsi }}</textarea>
</div>
