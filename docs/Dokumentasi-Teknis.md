# Dokumentasi Teknis SnackFlow

## Teknologi yang Digunakan

- Laravel sebagai framework backend.
- Blade sebagai template tampilan.
- MySQL sebagai database.
- Vite sebagai bundler frontend.
- JavaScript untuk interaksi halaman seperti modal, keranjang, katalog, dan checkout.
- RajaOngkir untuk layanan pengiriman.
- Midtrans untuk pembayaran online.

## Struktur Program

### Controller

Controller mengatur alur request dari user.

- `AuthController`: menangani registrasi, login, logout, dan update profil.
- `KatalogProdukController`: menangani katalog produk, tambah produk, edit produk, hapus produk, dan keranjang.
- `CheckoutController`: menangani halaman checkout, autocomplete alamat, ongkir, pembuatan transaksi, dan halaman pembayaran.
- `TransaksiController`: menangani riwayat transaksi user, transaksi admin, transaksi offline, konfirmasi admin, pembatalan, dan pesanan diterima.
- `MidtransController`: menerima webhook atau notification dari Midtrans.
- `ResetPasswordController`: menangani reset password melalui OTP email.
- `MapsController`: menyediakan pencarian area melalui endpoint API.

### Service

Service digunakan untuk memisahkan logic khusus dari controller.

- `RajaOngkirService`: berkomunikasi dengan API RajaOngkir.
- `MidtransService`: berkomunikasi dengan Midtrans.
- `KeranjangService`: menyinkronkan isi keranjang dengan stok terbaru.

### Model

Model menghubungkan Laravel dengan tabel database.

- `User`
- `KatalogProduk`
- `Keranjang`
- `DetailKeranjang`
- `Transaksi`
- `DetailTransaksi`
- `Penerima`
- `KodePos`
- `Kecamatan`
- `Kabupaten`
- `Provinsi`

### View

View menggunakan Blade.

- `landingPage.blade.php`: halaman landing page.
- `authView/FormLogin.blade.php`: halaman login.
- `authView/FormRegister.blade.php`: halaman registrasi.
- `katalog/HalamanKatalogProduk.blade.php`: katalog admin.
- `katalog/HalamanKatalogProdukU.blade.php`: katalog user.
- `transactions/FormPesanan.blade.php`: halaman checkout.
- `transactions/InvoicePembayaran.blade.php`: halaman invoice dan pembayaran.
- `transactions/HalamanRiwayatTransaksi.blade.php`: transaksi admin.
- `transactions/HalamanRiwayatTransaksiU.blade.php`: riwayat transaksi user.
- `profile/HalProfil.blade.php`: halaman profil.

## Route Utama

### Public

- `GET /`: landing page.
- `GET /login`: halaman login.
- `POST /login`: proses login.
- `GET /registrasi`: halaman registrasi.
- `POST /register`: proses registrasi.
- `POST /logout`: proses logout.
- `POST /midtrans/notification`: webhook Midtrans.

### Admin

Prefix route: `/admin`

- `GET /admin/katalog`: halaman katalog admin.
- `POST /admin/katalog/tambah`: tambah produk.
- `PUT /admin/katalog/update/{id}`: update produk.
- `DELETE /admin/katalog/hapus/{id}`: hapus produk.
- `GET /admin/transaksi`: halaman transaksi admin.
- `POST /admin/transaksi/offline`: tambah transaksi offline.
- `PUT /admin/transaksi/offline/{transaksi}`: edit transaksi offline.
- `POST /admin/transaksi/{transaksi}/approve`: konfirmasi pesanan.
- `POST /admin/transaksi/{transaksi}/reject`: batalkan pesanan.

### User

Prefix route: `/user`

- `GET /user/katalog`: halaman katalog user.
- `POST /user/keranjang`: tambah produk ke keranjang.
- `PATCH /user/keranjang/{id}`: update jumlah keranjang.
- `DELETE /user/keranjang/{id}`: hapus item keranjang.
- `GET /user/checkout`: halaman checkout.
- `GET /user/checkout/destination-autocomplete`: autocomplete alamat.
- `POST /user/checkout/rates`: hitung ongkir otomatis.
- `POST /user/checkout/proceed`: membuat transaksi.
- `GET /user/checkout/payment/{transaksi}`: halaman pembayaran.
- `POST /user/checkout/payment/{transaksi}/refresh-status`: cek ulang status pembayaran.
- `GET /user/transaksi`: riwayat transaksi user.
- `POST /user/transaksi/{transaksi}/received`: pesanan diterima.

## Detail Alur Keranjang

Keranjang dikelola oleh `KatalogProdukController` dan `KeranjangService`.

Saat user menambahkan produk ke keranjang:

1. Sistem menerima `produk_id` dan `jumlah_produk`.
2. Sistem mengecek stok produk.
3. Jika jumlah melebihi stok, sistem menolak request.
4. Jika valid, item disimpan ke `detail_keranjang`.
5. Stok produk tidak dikurangi pada tahap ini.

Saat halaman katalog atau checkout dibuka, `KeranjangService` menjalankan proses rekonsiliasi:

1. Sistem mengambil isi keranjang user.
2. Sistem membandingkan jumlah item dengan stok terbaru.
3. Jika jumlah di keranjang lebih besar dari stok, item dihapus.
4. Jika keranjang kosong, header keranjang juga dihapus.

Tujuan alur ini adalah mencegah stok tertahan oleh user yang hanya memasukkan produk ke keranjang tanpa melakukan checkout.

## Detail Alur Checkout

Checkout dikelola oleh `CheckoutController`.

Mode checkout dibagi menjadi:

- `cart`: checkout dari isi keranjang.
- `direct`: checkout langsung dari satu produk yang dipilih.

Pada mode `cart`, semua item di keranjang masuk ke halaman checkout.

Pada mode `direct`, sistem mengambil data dari session `direct_checkout` sehingga hanya produk yang dipilih yang masuk ke checkout.

Saat checkout diproses:

1. Sistem mengambil data penerima dari session.
2. Sistem mengambil tujuan pengiriman dari session.
3. Sistem mengambil ongkir dari session.
4. Sistem mengecek stok produk dengan `lockForUpdate`.
5. Sistem membuat data wilayah jika belum ada.
6. Sistem membuat data penerima.
7. Sistem membuat transaksi.
8. Sistem membuat detail transaksi.
9. Sistem mengurangi stok produk.
10. Jika mode checkout adalah `cart`, sistem menghapus isi keranjang.
11. Jika mode checkout adalah `direct`, isi keranjang tidak dihapus.

## Detail Alur Pengiriman

Pengiriman menggunakan `RajaOngkirService`.

Fitur yang digunakan:

- `searchDomesticDestinations`: mencari tujuan pengiriman dari input user.
- `calculateDomesticCost`: menghitung ongkir berdasarkan origin toko, tujuan, dan berat produk.

Sistem hanya menggunakan:

- Kurir: JNE
- Layanan: REG

Setelah user memilih tujuan dari autocomplete, sistem otomatis menghitung ongkir dan memasangnya ke checkout. Karena hanya memakai satu layanan pengiriman, user tidak perlu memilih kurir manual.

## Detail Alur Pembayaran

Pembayaran menggunakan `MidtransService`.

Saat user membuka halaman pembayaran:

1. Sistem mengecek apakah pesanan sudah dikonfirmasi admin.
2. Jika sudah, sistem membuat atau mengambil Snap Token.
3. Snap Token dipakai untuk membuka pembayaran Midtrans.
4. Metode pembayaran yang diaktifkan adalah `gopay`.
5. Midtrans mengirim notification ke endpoint `/midtrans/notification`.
6. Sistem memperbarui status transaksi berdasarkan status dari Midtrans.

Status dari Midtrans dipetakan sebagai berikut:

- `capture` atau `settlement`: transaksi menjadi `Diproses`, pembayaran menjadi `paid`, dan resi dibuat.
- `pending`: transaksi tetap `Dikonfirmasi`, pembayaran tetap `pending`.
- `deny`, `cancel`, atau `expire`: transaksi menjadi `Dibatalkan`, pembayaran menjadi `dibatalkan`.

## Detail Alur Transaksi Offline

Transaksi offline dikelola oleh `TransaksiController`.

Admin dapat mencatat pembelian toko secara manual. Data yang dimasukkan meliputi:

- nama penerima
- tanggal transaksi
- metode pembayaran
- nomor telepon penerima
- alamat
- kode pos
- kecamatan
- kabupaten
- provinsi
- resi
- ongkir
- produk dan jumlah produk

Produk dipilih dari dropdown. Jika admin memilih produk yang sama lebih dari satu kali, sistem menggabungkan jumlahnya.

Saat transaksi offline disimpan:

1. Sistem validasi input.
2. Sistem normalisasi item produk.
3. Sistem mengecek stok dengan `lockForUpdate`.
4. Sistem membuat data wilayah dan penerima.
5. Sistem membuat transaksi dengan status `Selesai`.
6. Sistem membuat detail transaksi.
7. Sistem mengurangi stok produk.
8. `midtrans_order_id` dibuat `null` sebagai penanda transaksi offline.

## Cara Menjalankan Program

Jalankan dependency PHP:

```powershell
composer install
```

Jalankan dependency frontend:

```powershell
npm install
```

Siapkan konfigurasi environment:

```powershell
copy .env.example .env
php artisan key:generate
```

Jalankan migrasi database:

```powershell
php artisan migrate:fresh --seed
```

Build asset frontend:

```powershell
npm run build
```

Jalankan server Laravel:

```powershell
php artisan serve
```

Jika perlu public URL untuk webhook Midtrans:

```powershell
ngrok http 8000
```

## Konfigurasi Environment Penting

Variabel `.env` yang perlu diperhatikan:

- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `MAIL_MAILER`
- `MAIL_HOST`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MIDTRANS_SERVER_KEY`
- `MIDTRANS_CLIENT_KEY`
- `MIDTRANS_IS_PRODUCTION`
- `RAJAONGKIR_API_KEY`
- `RAJAONGKIR_BASE_URL`
- `RAJAONGKIR_ORIGIN_SEARCH`
- `RAJAONGKIR_COURIERS`

