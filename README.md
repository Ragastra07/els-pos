# ELS POS Simple

ELS POS Simple adalah aplikasi Point of Sale sederhana untuk mencatat transaksi penjualan produk toko komputer. Project ini dibuat sebagai tugas interview online Staff Programmer ELS.ID Yogyakarta.

Aplikasi ini dibuat menggunakan CodeIgniter 4, MariaDB/MySQL, Bootstrap 5, dan JavaScript.

## Tech Stack

- PHP
- CodeIgniter 4
- MariaDB / MySQL
- Bootstrap 5
- JavaScript
- Composer

## Fitur Aplikasi

- Login admin/kasir
- Logout
- Dashboard sederhana
- Daftar produk
- Pencatatan transaksi penjualan
- Perhitungan subtotal, total, pembayaran, dan kembalian
- Riwayat transaksi penjualan
- Detail transaksi penjualan
- Pengurangan stok produk otomatis setelah transaksi berhasil disimpan

## Akun Login Aplikasi

Gunakan akun berikut untuk login ke aplikasi:

```text
Username: admin
Password: admin123
```

Password pada database sudah disimpan dalam bentuk hash, bukan plain text.

## Konfigurasi Database Aplikasi

Aplikasi menggunakan konfigurasi database berikut:

```text
Database Name : els_pos
Database User : els_user
Database Pass : els_password
Host          : localhost
Port          : 3306
Driver        : MySQLi
```

Konfigurasi tersebut digunakan pada file `.env`:

```ini
database.default.hostname = localhost
database.default.database = els_pos
database.default.username = els_user
database.default.password = els_password
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

## File Database

File export database tersedia di root project:

```text
els_pos.sql
```

File tersebut sudah berisi:

- `CREATE DATABASE`
- `USE els_pos`
- Struktur tabel
- Data awal user admin
- Data awal kategori
- Data awal produk

Data transaksi pada tabel `sales` dan `sale_items` dikosongkan agar database awal tetap bersih.

## Struktur Database

Database menggunakan 5 tabel utama:

### 1. `users`

Digunakan untuk menyimpan data user yang dapat login ke aplikasi.

Kolom utama:

- `id`
- `name`
- `username`
- `password`
- `role`
- `created_at`
- `updated_at`

### 2. `categories`

Digunakan untuk menyimpan kategori produk.

Kolom utama:

- `id`
- `name`
- `created_at`
- `updated_at`

### 3. `products`

Digunakan untuk menyimpan data produk toko komputer.

Kolom utama:

- `id`
- `category_id`
- `product_code`
- `name`
- `purchase_price`
- `selling_price`
- `stock`
- `unit`
- `created_at`
- `updated_at`

### 4. `sales`

Digunakan untuk menyimpan data utama atau header transaksi penjualan.

Kolom utama:

- `id`
- `user_id`
- `invoice_number`
- `customer_name`
- `total_amount`
- `paid_amount`
- `change_amount`
- `sale_date`
- `created_at`
- `updated_at`

### 5. `sale_items`

Digunakan untuk menyimpan detail produk pada setiap transaksi penjualan.

Kolom utama:

- `id`
- `sale_id`
- `product_id`
- `qty`
- `price`
- `subtotal`
- `created_at`
- `updated_at`

## Relasi Database

Relasi utama pada aplikasi:

- Satu user dapat memiliki banyak transaksi penjualan.
- Satu kategori dapat memiliki banyak produk.
- Satu transaksi penjualan dapat memiliki banyak item transaksi.
- Satu produk dapat muncul di banyak item transaksi.

Secara sederhana:

```text
users       -> sales
categories  -> products
sales       -> sale_items
products    -> sale_items
```

## Cara Instalasi

Clone repository:

```bash
git clone https://github.com/Ragastra07/els-pos.git
cd els-pos
```

Install dependency menggunakan Composer:

```bash
composer install
```

Copy file environment:

```bash
cp env .env
```

Atur environment pada file `.env`:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
```

## Setup Database

Import database menggunakan user root atau user database yang memiliki privilege untuk membuat database:

```bash
mysql -u root -p < els_pos.sql
```

File `els_pos.sql` sudah membuat database `els_pos`, sehingga tidak perlu membuat database secara manual terlebih dahulu jika import menggunakan user yang memiliki privilege `CREATE DATABASE`.

Setelah database berhasil di-import, buat user database untuk aplikasi:

```sql
CREATE USER IF NOT EXISTS 'els_user'@'localhost' IDENTIFIED BY 'els_password';

GRANT ALL PRIVILEGES ON els_pos.* TO 'els_user'@'localhost';

FLUSH PRIVILEGES;
```

Atur konfigurasi database pada file `.env`:

```ini
database.default.hostname = localhost
database.default.database = els_pos
database.default.username = els_user
database.default.password = els_password
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

Jalankan aplikasi:

```bash
php spark serve
```

Buka aplikasi di browser:

```text
http://localhost:8080
```

## Cara Penggunaan

### 1. Login

Buka halaman aplikasi, lalu login menggunakan akun demo:

```text
Username: admin
Password: admin123
```

### 2. Lihat Produk

Setelah login, buka menu `Produk` untuk melihat daftar produk toko komputer.

Contoh produk:

- Mouse Logitech M170
- Keyboard Mechanical
- SSD 256GB
- Kabel HDMI 1.5M
- Flashdisk 32GB
- Printer Canon Inkjet

### 3. Buat Transaksi Penjualan

Buka menu `Penjualan`, lalu:

1. Isi nama customer.
2. Pilih produk.
3. Isi jumlah pembelian.
4. Sistem akan menghitung subtotal dan total.
5. Isi nominal pembayaran.
6. Sistem akan menghitung kembalian.
7. Klik tombol `Simpan Transaksi`.

### 4. Lihat Detail Transaksi

Setelah transaksi berhasil disimpan, aplikasi akan menampilkan halaman detail transaksi.

Detail transaksi berisi:

- Nomor invoice
- Nama customer
- Nama kasir
- Tanggal transaksi
- Total transaksi
- Nominal pembayaran
- Kembalian
- Daftar produk yang dibeli

### 5. Lihat Riwayat Transaksi

Buka menu `Riwayat Transaksi` untuk melihat daftar transaksi yang sudah tersimpan.

### 6. Cek Stok Produk

Setelah transaksi berhasil disimpan, stok produk otomatis berkurang sesuai jumlah produk yang dibeli.

Contoh:

```text
Stok awal Mouse Logitech M170 : 20
Qty terjual                   : 2
Stok akhir                    : 18
```

## Alur Aplikasi

1. User membuka aplikasi.
2. User login menggunakan username dan password.
3. Jika login berhasil, user masuk ke dashboard.
4. User dapat melihat daftar produk.
5. User membuat transaksi penjualan baru.
6. User memilih produk dan mengisi jumlah pembelian.
7. Sistem menghitung subtotal, total, pembayaran, dan kembalian.
8. User menyimpan transaksi.
9. Sistem menyimpan data utama transaksi ke tabel `sales`.
10. Sistem menyimpan detail produk ke tabel `sale_items`.
11. Sistem mengurangi stok produk secara otomatis.
12. User dapat melihat detail dan riwayat transaksi.

## Catatan Teknis

Pada proses penyimpanan transaksi penjualan, aplikasi menggunakan database transaction dari CodeIgniter 4.

Proses yang dilakukan dalam satu transaction:

1. Insert data ke tabel `sales`.
2. Insert data detail ke tabel `sale_items`.
3. Update stok produk pada tabel `products`.

Tujuan penggunaan database transaction adalah agar data transaksi tetap konsisten. Jika salah satu proses gagal, transaksi tidak dianggap berhasil sehingga data tidak setengah tersimpan.

Contoh masalah yang ingin dihindari:

```text
Data sales tersimpan,
tetapi sale_items gagal tersimpan,
atau stok produk gagal berkurang.
```

Dengan database transaction, proses tersebut diperlakukan sebagai satu kesatuan.

## Struktur Folder Utama

```text
app/
├── Controllers/
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── ProductController.php
│   └── SaleController.php
│
├── Models/
│   ├── UserModel.php
│   ├── CategoryModel.php
│   ├── ProductModel.php
│   ├── SaleModel.php
│   └── SaleItemModel.php
│
├── Views/
│   ├── auth/
│   │   └── login.php
│   │
│   ├── dashboard/
│   │   └── index.php
│   │
│   ├── layouts/
│   │   └── main.php
│   │
│   ├── products/
│   │   └── index.php
│   │
│   └── sales/
│       ├── create.php
│       ├── index.php
│       └── show.php
```

## Controller

### `AuthController`

Digunakan untuk menangani:

- Halaman login
- Proses login
- Logout
- Penyimpanan data user ke session

### `DashboardController`

Digunakan untuk menampilkan halaman dashboard setelah user berhasil login.

### `ProductController`

Digunakan untuk menampilkan daftar produk dari database.

### `SaleController`

Digunakan untuk menangani proses transaksi penjualan, meliputi:

- Menampilkan riwayat transaksi
- Menampilkan form transaksi baru
- Menyimpan transaksi
- Menampilkan detail transaksi
- Mengurangi stok produk

## Model

### `UserModel`

Terhubung dengan tabel `users`.

### `CategoryModel`

Terhubung dengan tabel `categories`.

### `ProductModel`

Terhubung dengan tabel `products`.

### `SaleModel`

Terhubung dengan tabel `sales`.

### `SaleItemModel`

Terhubung dengan tabel `sale_items`.

## Scope Project

Project ini dibuat sesuai requirement tugas, yaitu:

- Login
- Pencatatan penjualan
- Data disimpan ke MariaDB/MySQL
- Menggunakan CodeIgniter 4
- Menggunakan Bootstrap untuk tampilan

Beberapa fitur tambahan yang dibuat agar alur POS lebih realistis:

- Daftar produk
- Riwayat transaksi
- Detail transaksi
- Pengurangan stok otomatis

## Skenario Demo Singkat

Skenario yang dapat digunakan saat presentasi:

1. Login menggunakan akun admin.
2. Buka halaman produk untuk menunjukkan data produk.
3. Buka halaman penjualan.
4. Pilih produk dan isi jumlah pembelian.
5. Masukkan nominal pembayaran.
6. Simpan transaksi.
7. Tampilkan detail transaksi.
8. Buka riwayat transaksi.
9. Buka halaman produk untuk menunjukkan stok berkurang.

## Pengembangan Selanjutnya

Jika aplikasi dikembangkan lebih lanjut, beberapa fitur yang dapat ditambahkan adalah:

- CRUD produk lengkap
- Manajemen user kasir
- Filter laporan penjualan berdasarkan tanggal
- Cetak struk
- Retur barang
- Manajemen supplier
- Manajemen pelanggan
- Export laporan ke PDF atau Excel
- Dashboard statistik penjualan

## Author

Ragastra Haryo Wijanarko