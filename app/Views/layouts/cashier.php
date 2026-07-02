<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo esc($title ?? 'ELS POS Cashier') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="<?php echo base_url('/sales/cashier') ?>">
            ELS POS Cashier
        </a>

        <div class="d-flex align-items-center gap-2 text-white">
            <span class="small">
                <?php echo esc(session()->get('name')) ?>
            </span>
            <a href="<?php echo base_url('/dashboard') ?>" class="btn btn-outline-light btn-sm">
                Dashboard
            </a>
            <a href="<?php echo base_url('/logout') ?>" class="btn btn-light btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>

<main class="cashier-shell">
    <?php echo $this->renderSection('content') ?>
</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
</body>
</html>
