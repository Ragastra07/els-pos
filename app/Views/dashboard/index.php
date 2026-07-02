<!-- Use the main layout as the base template for this page. -->
<?= $this->extend('layouts/main') ?>

<!-- Start content section that will be rendered inside layouts/main.php. -->
<?= $this->section('content') ?>

<!-- Dashboard welcome card. -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h4 class="fw-bold mb-1">Dashboard</h4>
        <p class="text-muted mb-0">
            Login berhasil. Selamat datang di aplikasi POS sederhana ELS.
        </p>
    </div>
</div>

<!-- Dashboard shortcut cards for main application modules. -->
<div class="row mt-4">

    <!-- Product module shortcut. -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Master Data</h6>
                <h5>Produk</h5>
                <p class="mb-3 small text-muted">
                    Kelola dan lihat data produk toko komputer.
                </p>

                <!-- Link to product list page. -->
                <a href="<?= base_url('/products') ?>" class="btn btn-primary btn-sm">
                    Lihat Produk
                </a>
            </div>
        </div>
    </div>

    <!-- Sales module placeholder. This feature will be implemented in the next step. -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Transaksi</h6>
                <h5>Penjualan</h5>
                <p class="mb-0 small text-muted">
                    Menu ini digunakan untuk mencatat transaksi penjualan.
                </p>

                <!-- Link to sales transaction page. -->
                <a href="<?= base_url('/sales/create') ?>" class="btn btn-primary btn-sm">
                    Buat Penjualan
                </a>
            </div>
        </div>
    </div>

    <!-- Sales history module placeholder. -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Riwayat</h6>
                <h5>Riwayat Transaksi</h5>
                <p class="mb-0 small text-muted">
                    Menu ini digunakan untuk melihat transaksi yang sudah tersimpan.
                </p>

                <!-- Link to sales history page. -->
                <a href="<?= base_url('/sales') ?>" class="btn btn-primary btn-sm">
                    Lihat Riwayat
                </a>
            </div>
        </div>
    </div>
</div>

<!-- End content section. -->
<?= $this->endSection() ?>