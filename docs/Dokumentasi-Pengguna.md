# Dokumentasi Pengguna SnackFlow

## Gambaran Umum

SnackFlow adalah aplikasi web untuk membantu proses penjualan produk UMKM Matrix Jaya. Melalui sistem ini, pengunjung dapat melihat produk dari landing page, sedangkan pelanggan yang sudah login dapat melihat katalog produk, memasukkan produk ke keranjang, melakukan checkout, membayar pesanan secara online, memberi review, membaca artikel, dan melihat riwayat transaksi.

Admin dapat mengelola katalog produk, memantau transaksi online, mengonfirmasi pesanan, membatalkan pesanan, mencatat pembelian offline toko, mengelola artikel, melihat review produk, dan melihat laporan penjualan serta keuangan.

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
- Melihat review produk.
- Mengelola artikel.
- Melihat laporan penjualan.
- Melihat laporan keuangan.
- Menambah, mengedit, dan menghapus data pengeluaran dari laporan keuangan.

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
- Memberi, melihat, dan menghapus review pada transaksi selesai.
- Melihat semua review dari card katalog produk.
- Melihat daftar dan detail artikel.
- Reset password menggunakan OTP email.

### Guest

Guest dapat melihat produk dan ringkasan artikel pada landing page tanpa login. Jika guest klik tombol beli atau ingin membuka seluruh artikel melalui dashboard user, sistem mengarahkan guest ke halaman login terlebih dahulu.

## Alur Katalog Produk

### Admin

1. Admin membuka halaman katalog.
2. Admin dapat menambah produk melalui tombol tambah produk.
3. Admin mengisi data produk seperti nama produk, harga, stok, berat, deskripsi, dan foto produk.
4. Sistem menyimpan data produk ke tabel `katalog_produk`.
5. Admin dapat klik card produk untuk melihat detail.
6. Dari detail produk, admin dapat memilih edit atau hapus.
7. Admin dapat klik tombol review pada detail produk untuk melihat semua review user pada produk tersebut.

### User

1. User membuka halaman katalog.
2. User melihat daftar produk yang tersedia.
3. User dapat klik produk untuk melihat detail produk.
4. User memilih jumlah produk.
5. User dapat memilih `Add to Cart` atau `Checkout`.
6. User dapat klik `Lihat Review` pada card produk untuk melihat semua review produk.

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

## Alur Review Produk

1. User membuka menu transaksi.
2. User klik review pada transaksi yang statusnya `Selesai`.
3. Jika review sudah pernah dibuat, sistem menampilkan review tersebut.
4. Jika belum ada review, sistem menampilkan form review.
5. User memilih rating bintang 1 sampai 5 dan mengisi review produk.
6. Foto review boleh dikosongkan.
7. User klik kirim.
8. Jika berhasil, sistem menampilkan pesan `Review Produk Berhasil Dilakukan`.
9. Jika data wajib kosong, sistem menampilkan pesan `Data Tidak Boleh Kosong`.
10. Jika data tidak sesuai, sistem menampilkan pesan `Data Tidak Sesuai`.
11. User dapat menghapus review dari halaman review transaksi dengan konfirmasi popup.

## Alur Artikel

Admin:

1. Admin membuka menu artikel.
2. Sistem menampilkan daftar artikel.
3. Admin dapat menambah artikel dengan judul, konten artikel, dan gambar opsional.
4. Admin dapat klik artikel untuk melihat detail.
5. Admin dapat mengedit atau menghapus artikel dari halaman detail.

User:

1. User membuka menu artikel.
2. Sistem menampilkan daftar artikel.
3. User klik salah satu artikel untuk melihat detail artikel.

## Alur Laporan Admin

1. Admin klik menu laporan pada sidebar.
2. Sistem membuka sub menu laporan penjualan dan laporan keuangan.
3. Admin klik laporan penjualan untuk melihat grafik transaksi, penjualan produk, total transaksi, produk terlaris, dan total penjualan produk.
4. Admin klik laporan keuangan untuk melihat grafik income, grafik pengeluaran, grafik profit, total income, total pengeluaran, profit, dan data pengeluaran.
5. Admin dapat memilih periode bulan dan tahun pada laporan.
6. Data pengeluaran ditambah, diedit, dan dihapus dari halaman laporan keuangan.

## Alur Reset Password OTP

1. User membuka halaman reset password.
2. User memasukkan email.
3. Sistem membuat kode OTP.
4. OTP dikirim ke email user.
5. User memasukkan OTP.
6. Jika OTP benar dan belum kedaluwarsa, user dapat membuat password baru.
