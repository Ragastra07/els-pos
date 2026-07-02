<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <!-- Dynamic page title. If no title is provided, use the default app name. -->
    <title><?php echo esc($title ?? 'ELS POS Simple') ?></title>

    <!-- Make the layout responsive on mobile and desktop screens. -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS from CDN for quick UI styling without custom CSS setup. -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<!-- bg-light gives the entire page a soft gray background. -->
<body class="bg-light">

<!-- Top navigation bar. This area shows the app name, logged-in user, and logout button. -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="<?php echo base_url('/dashboard') ?>">
            ELS POS Simple
        </a>

        <!-- Display current logged-in user information from session. -->
        <div class="d-flex align-items-center text-white">
            <span class="me-3">
                <?php echo esc(session()->get('name')) ?>
                (<?php echo esc(session()->get('role')) ?>)
            </span>

            <!-- Logout button redirects to AuthController::logout. -->
            <a href="<?php echo base_url('/logout') ?>" class="btn btn-outline-light btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>

<!-- Main page wrapper using Bootstrap grid. -->
<div class="container-fluid">
    <div class="row">

        <!-- Sidebar navigation menu. -->
        <!-- min-vh-100 makes the sidebar height follow the full viewport height. -->
        <aside class="col-md-2 col-lg-2 bg-white border-end min-vh-100 p-3">
            <div class="list-group list-group-flush">

                <!-- Navigation link to dashboard page. -->
                <a href="<?php echo base_url('/dashboard') ?>" class="list-group-item list-group-item-action">
                    Dashboard
                </a>

                <!-- Navigation link to product list page. -->
                <a href="<?php echo base_url('/products') ?>" class="list-group-item list-group-item-action">
                    Produk
                </a>

                <!-- Disabled-looking menu placeholder for the next feature. -->
                <a href="<?php echo base_url('/sales/create') ?>" class="list-group-item list-group-item-action">
                    Penjualan
                </a>


                <!-- Disabled-looking menu placeholder for sales history feature. -->
                <a href="<?= base_url('/sales') ?>" class="list-group-item list-group-item-action">
                    Riwayat Transaksi
                </a>
            </div>
        </aside>

        <!-- Main content area. Each page will inject its content into this section. -->
        <main class="col-md-10 col-lg-10 p-4">
            <?php echo $this->renderSection('content') ?>
        </main>
    </div>
</div>

<!-- Bootstrap JS bundle for interactive components if needed later. -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>