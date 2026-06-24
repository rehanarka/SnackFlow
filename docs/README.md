# Dokumentasi SnackFlow

Folder ini berisi dokumentasi utama program SnackFlow. Dokumentasi dibagi menjadi beberapa bagian supaya mudah dipakai untuk laporan, presentasi, dan penjelasan teknis.

## Daftar Dokumen

- [Dokumentasi Pengguna](Dokumentasi-Pengguna.md)
- [Dokumentasi Teknis](Dokumentasi-Teknis.md)
- [Dokumentasi Database dan Integrasi](Dokumentasi-Database-dan-Integrasi.md)

## Ringkasan Sistem

SnackFlow adalah sistem informasi penjualan produk kuliner UMKM Matrix Jaya. Sistem ini digunakan untuk mengelola katalog produk, keranjang belanja, checkout, transaksi online, transaksi offline toko, pengiriman, pembayaran, riwayat pesanan, review produk, artikel, serta laporan penjualan dan keuangan.

Sistem memiliki dua jenis pengguna utama:

- Admin, yang bertugas mengelola produk, transaksi, artikel, review, laporan penjualan, dan laporan keuangan.
- User, yang melakukan pemesanan produk secara online, membaca artikel, dan memberi review pada transaksi selesai.

Guest atau pengunjung tanpa login dapat melihat produk dan ringkasan artikel di landing page. Landing page juga menyediakan tombol WhatsApp mengambang di kanan bawah untuk menghubungi Matrix Jaya melalui `https://wa.me/6281515400001`. Saat ingin membeli atau melihat seluruh artikel melalui dashboard user, pengunjung diarahkan untuk login terlebih dahulu.

Integrasi eksternal yang digunakan:

- RajaOngkir untuk pencarian alamat dan perhitungan ongkir.
- Midtrans untuk pembayaran online.
