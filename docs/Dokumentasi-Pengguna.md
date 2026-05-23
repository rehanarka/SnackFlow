# Dokumentasi Pengguna SnackFlow

## Gambaran Umum

SnackFlow adalah aplikasi web untuk membantu proses penjualan produk UMKM Matrix Jaya. Melalui sistem ini, pelanggan dapat melihat katalog produk, memasukkan produk ke keranjang, melakukan checkout, membayar pesanan secara online, dan melihat riwayat transaksi.

Admin dapat mengelola katalog produk, memantau transaksi online, mengonfirmasi pesanan, membatalkan pesanan, dan mencatat pembelian offline toko agar tetap masuk ke data transaksi.

## Role Pengguna

### Admin

Admin memiliki akses untuk:

- Login ke sistem.
- Mengelola data katalog produk.
- Menambah produk baru.
- Mengedit produk.
- Menghapus produk.
- Melihat daftar transaksi.
- Mengonfirmasi pesanan online.
- Membatalkan pesanan online.
- Menambah transaksi offline toko.
- Mengedit transaksi offline.
- Melihat detail transaksi.

### User

User memiliki akses untuk:

- Registrasi akun.
- Login ke sistem.
- Melihat katalog produk.
- Melihat detail produk.
- Menambahkan produk ke keranjang.
- Mengubah jumlah produk di keranjang.
- Menghapus produk dari keranjang.
- Melakukan checkout dari keranjang.
- Melakukan checkout langsung dari produk tertentu.
- Mengisi data penerima.
- Memilih alamat tujuan melalui autocomplete.
- Melakukan pembayaran.
- Melihat riwayat transaksi.
- Mengonfirmasi pesanan diterima.
- Reset password menggunakan OTP email.

## Alur Katalog Produk

### Admin

1. Admin membuka halaman katalog.
2. Admin dapat menambah produk melalui tombol tambah produk.
3. Admin mengisi data produk seperti nama produk, harga, stok, berat, deskripsi, dan foto produk.
4. Sistem menyimpan data produk ke tabel `katalog_produk`.
5. Admin dapat klik card produk untuk melihat detail.
6. Dari detail produk, admin dapat memilih edit atau hapus.

### User

1. User membuka halaman katalog.
2. User melihat daftar produk yang tersedia.
3. User dapat klik produk untuk melihat detail produk.
4. User memilih jumlah produk.
5. User dapat memilih `Add to Cart` atau `Checkout`.

## Alur Keranjang

1. User klik `Add to Cart` pada produk.
2. Produk masuk ke keranjang dengan jumlah yang dipilih.
3. Stok produk belum dikurangi saat masuk keranjang.
4. User dapat menambah atau mengurangi jumlah produk di keranjang.
5. Jika stok terbaru tidak mencukupi jumlah produk yang ada di keranjang, sistem otomatis menghapus item tersebut dari keranjang.
6. Setelah aksi tambah, update, atau hapus keranjang, modal keranjang tetap terbuka setelah halaman refresh.

Catatan penting: stok baru dikurangi saat transaksi benar-benar dibuat melalui proses checkout.

## Alur Checkout Keranjang

1. User membuka modal keranjang.
2. User klik tombol `Checkout`.
3. Sistem membuka halaman checkout dengan mode `Checkout Keranjang`.
4. Sistem menampilkan semua produk yang ada di keranjang.
5. User mengisi data penerima.
6. User memilih tujuan pengiriman melalui autocomplete RajaOngkir.
7. Sistem otomatis menghitung dan memasang ongkir JNE Reguler.
8. User klik tombol checkout.
9. Sistem membuat transaksi dengan status `Menunggu Konfirmasi`.
10. Stok produk dikurangi sesuai jumlah produk yang dibeli.
11. Keranjang user dikosongkan.

## Alur Checkout Langsung

1. User memilih jumlah produk pada card katalog.
2. User klik tombol `Checkout`.
3. Sistem membuka halaman checkout dengan mode `Checkout Langsung`.
4. Sistem hanya membawa produk yang dipilih, bukan seluruh isi keranjang.
5. User mengisi data penerima.
6. User memilih tujuan pengiriman.
7. Sistem otomatis memasang ongkir JNE Reguler.
8. User membuat transaksi.
9. Stok produk dikurangi saat transaksi dibuat.

Checkout langsung tidak menghapus isi keranjang karena alurnya terpisah dari checkout keranjang.

## Alur Pembayaran Online

1. User berhasil membuat transaksi.
2. Status transaksi awal adalah `Menunggu Konfirmasi`.
3. Admin mengonfirmasi pesanan.
4. Status transaksi berubah menjadi `Dikonfirmasi`.
5. User membuka halaman pembayaran.
6. Sistem membuat Snap Token Midtrans.
7. User melakukan pembayaran melalui Midtrans.
8. Jika pembayaran berhasil, status berubah menjadi `Diproses`.
9. Sistem otomatis membuat nomor resi dengan format `JNE-XXXXXXXXXX`.
10. User dapat melihat resi pada detail pesanan.
11. Setelah pesanan diterima, user klik `Pesanan Diterima`.
12. Status berubah menjadi `Selesai`.

## Alur Transaksi Offline Admin

1. Admin membuka halaman transaksi.
2. Admin klik tombol tambah transaksi offline.
3. Admin mengisi data penerima, alamat, wilayah, tanggal transaksi, metode pembayaran, resi, ongkir, dan produk yang dibeli.
4. Produk dipilih melalui dropdown.
5. Jika pembelian berisi banyak produk, admin dapat menambah beberapa baris produk.
6. Sistem menggabungkan produk yang sama agar tidak tercatat dobel.
7. Sistem mengecek stok produk.
8. Jika stok cukup, transaksi disimpan.
9. Status transaksi otomatis `Selesai`.
10. Status pembayaran otomatis `paid`.
11. `midtrans_order_id` dikosongkan sebagai penanda transaksi offline.

Metode pembayaran offline yang tersedia:

- QRIS
- COD
- Transfer Bank

## Alur Reset Password OTP

1. User membuka halaman reset password.
2. User memasukkan email.
3. Sistem membuat kode OTP.
4. OTP dikirim ke email user.
5. User memasukkan OTP.
6. Jika OTP benar dan belum kedaluwarsa, user dapat membuat password baru.

