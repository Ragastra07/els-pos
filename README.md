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
- Pencatatan transaksi penjualan melalui form standar
- Mode Kasir dengan layout POS interaktif
- Product grid dengan pencarian dan filter kategori
- Cart/order panel interaktif
- Numpad layar dan dukungan keyboard/numpad fisik
- Payment mode dengan perhitungan total, pembayaran, dan kembalian
- Riwayat transaksi penjualan
- Detail transaksi penjualan
- Cetak nota / simpan nota sebagai PDF melalui browser print dialog
- Pengurangan stok produk otomatis setelah transaksi berhasil disimpan
- Proteksi route menggunakan Auth Filter
- Proteksi form POST menggunakan CSRF
- Validasi stok pada backend
- Validasi duplicate product dalam satu transaksi

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

### 3. Buat Transaksi Penjualan Melalui Form Standar

Buka menu `Penjualan`, lalu:

1. Isi nama customer.
2. Pilih produk.
3. Isi jumlah pembelian.
4. Sistem akan menghitung subtotal dan total.
5. Isi nominal pembayaran.
6. Sistem akan menghitung kembalian.
7. Klik tombol `Simpan Transaksi`.

### 4. Buat Transaksi Melalui Mode Kasir

Selain form transaksi standar, aplikasi juga menyediakan fitur `Mode Kasir` agar proses penjualan lebih cepat dan lebih mirip dengan alur penggunaan POS retail.

Mode Kasir dapat diakses melalui menu:

```text
Mode Kasir
```

atau melalui URL:

```text
/sales/cashier
```

Fitur pada Mode Kasir:

- Product grid untuk memilih produk dengan cepat
- Search produk berdasarkan kode, nama, atau kategori
- Filter produk berdasarkan kategori
- Cart/order panel untuk melihat item transaksi
- Klik produk untuk memasukkan produk ke cart
- Klik produk yang sama untuk menambah qty
- Numpad layar untuk mengubah qty atau nominal pembayaran
- Dukungan keyboard/numpad fisik
- Payment mode untuk proses pembayaran
- Tombol `Uang Pas`
- Tombol `Lunas` untuk menyimpan transaksi

Alur Mode Kasir:

1. User membuka halaman Mode Kasir.
2. User memilih produk dari product grid.
3. Produk masuk ke cart.
4. Jika produk yang sama dipilih lagi, qty akan bertambah.
5. User dapat mengubah qty melalui numpad.
6. User klik tombol `Bayar`.
7. User masuk ke payment mode.
8. User mengisi nominal pembayaran atau klik `Uang Pas`.
9. User klik `Lunas`.
10. Sistem menyimpan transaksi ke database.
11. Sistem mengurangi stok produk.
12. User diarahkan ke halaman detail transaksi.

### 5. Lihat Detail Transaksi

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

### 6. Cetak / Simpan PDF Nota

Pada halaman detail transaksi tersedia tombol:

```text
Cetak / Simpan PDF Nota
```

Tombol tersebut akan membuka halaman nota sederhana yang dapat dicetak atau disimpan sebagai PDF menggunakan fitur print dari browser.

Alur cetak nota:

1. Buka detail transaksi.
2. Klik tombol `Cetak / Simpan PDF Nota`.
3. Halaman nota terbuka di tab baru.
4. Klik tombol `Print / Save PDF`.
5. Pilih printer atau pilih `Save as PDF`.

Fitur nota ini belum menggunakan library PDF tambahan. Untuk saat ini, nota dibuat sebagai halaman print-friendly agar tetap sederhana dan ringan.

### 7. Lihat Riwayat Transaksi

Buka menu `Riwayat Transaksi` untuk melihat daftar transaksi yang sudah tersimpan.

### 8. Cek Stok Produk

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
5. User dapat membuat transaksi melalui form standar atau Mode Kasir.
6. User memilih produk dan mengisi jumlah pembelian.
7. Sistem menghitung subtotal, total, pembayaran, dan kembalian.
8. User menyimpan transaksi.
9. Sistem menyimpan data utama transaksi ke tabel `sales`.
10. Sistem menyimpan detail produk ke tabel `sale_items`.
11. Sistem mengurangi stok produk secara otomatis.
12. User dapat melihat detail dan riwayat transaksi.
13. User dapat mencetak atau menyimpan nota transaksi sebagai PDF melalui browser.

## Catatan Teknis

### Database Transaction

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

### Validasi Duplicate Product

Pada proses transaksi, aplikasi menggabungkan produk yang sama terlebih dahulu sebelum melakukan validasi stok.

Contoh:

```text
Mouse Logitech M170 qty 2
Mouse Logitech M170 qty 3
```

Akan diproses sebagai:

```text
Mouse Logitech M170 qty 5
```

Hal ini dilakukan agar validasi stok dihitung berdasarkan total qty produk yang sama dalam satu transaksi, bukan hanya berdasarkan tiap baris input.

### Auth Filter

Aplikasi menggunakan Auth Filter untuk melindungi halaman yang hanya boleh diakses setelah login, seperti:

- Dashboard
- Produk
- Penjualan
- Mode Kasir
- Riwayat Transaksi
- Detail Transaksi
- Nota Transaksi

Jika user belum login dan mengakses halaman tersebut, sistem akan mengarahkan user ke halaman login.

### CSRF Protection

Form POST pada aplikasi sudah menggunakan CSRF protection, termasuk:

- Form login
- Form transaksi standar
- Form submit transaksi dari Mode Kasir

## Struktur Folder Utama

```text
app/
├── Controllers/
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── ProductController.php
│   └── SaleController.php
│
├── Filters/
│   └── AuthFilter.php
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
│   │   ├── main.php
│   │   └── cashier.php
│   │
│   ├── products/
│   │   └── index.php
│   │
│   └── sales/
│       ├── create.php
│       ├── index.php
│       ├── show.php
│       ├── cashier.php
│       └── receipt.php
│
public/
└── assets/
    ├── css/
    │   ├── cashier.css
    │   └── receipt.css
    │
    └── js/
        ├── cashier.js
        └── sales-create.js
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
- Menampilkan Mode Kasir
- Menyimpan transaksi
- Menampilkan detail transaksi
- Menampilkan nota transaksi
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
- Mode Kasir dengan product grid dan cart interaktif
- Payment mode
- Numpad layar dan keyboard support
- Nota penjualan print-friendly
- Pengurangan stok otomatis
- Auth Filter
- CSRF protection

## Skenario Demo Singkat

Skenario yang dapat digunakan saat presentasi:

1. Login menggunakan akun admin.
2. Buka halaman produk untuk menunjukkan data produk.
3. Buka `Mode Kasir`.
4. Pilih produk dari product grid.
5. Tunjukkan produk masuk ke cart.
6. Tambahkan produk yang sama untuk menunjukkan qty bertambah.
7. Gunakan numpad layar atau keyboard untuk mengubah qty.
8. Klik tombol `Bayar`.
9. Klik `Uang Pas` atau isi nominal pembayaran.
10. Klik `Lunas`.
11. Tampilkan detail transaksi.
12. Klik tombol `Cetak / Simpan PDF Nota`.
13. Tampilkan halaman nota penjualan.
14. Buka riwayat transaksi.
15. Buka halaman produk untuk menunjukkan stok berkurang.

## Pengembangan Selanjutnya

Jika aplikasi dikembangkan lebih lanjut, beberapa fitur yang dapat ditambahkan adalah:

- CRUD produk lengkap
- Manajemen user kasir
- Filter laporan penjualan berdasarkan tanggal
- Generate PDF nota menggunakan library PDF seperti Dompdf
- Cetak struk thermal printer
- Retur barang
- Manajemen supplier
- Manajemen pelanggan
- Export laporan ke PDF atau Excel
- Dashboard statistik penjualan
- Migration dan seeder CodeIgniter 4
- Foreign key antar tabel
- Automated testing untuk fitur inti

## Author

Ragastra Haryo Wijanarko