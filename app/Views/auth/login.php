<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - ELS POS Simple</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-1">ELS POS Simple</h4>
                        <p class="text-muted mb-0">Login Admin / Kasir</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo base_url('/login') ?>" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control"
                                value="<?php echo old('username') ?>"
                                autofocus
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>
                    </form>

                    <div class="mt-4 small text-muted">
                        <strong>Akun demo:</strong><br>
                        Username: admin<br>
                        Password: admin123
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>