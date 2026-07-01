<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - ELS POS Simple</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url('/dashboard') ?>">
            ELS POS Simple
        </a>

        <div class="d-flex align-items-center text-white">
            <span class="me-3">
                <?= esc(session()->get('name')) ?> 
                (<?= esc(session()->get('role')) ?>)
            </span>
            <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h4 class="fw-bold">Dashboard</h4>
            <p class="text-muted mb-0">
                Login berhasil. Selamat datang di aplikasi POS sederhana ELS.
            </p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Menu Berikutnya</h6>
                    <h5>Master Produk</h5>
                    <p class="mb-0 small text-muted">
                        Nanti digunakan untuk mengelola data produk toko komputer.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Menu Berikutnya</h6>
                    <h5>Penjualan</h5>
                    <p class="mb-0 small text-muted">
                        Nanti digunakan untuk mencatat transaksi penjualan.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Menu Berikutnya</h6>
                    <h5>Riwayat Transaksi</h5>
                    <p class="mb-0 small text-muted">
                        Nanti digunakan untuk melihat daftar transaksi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>