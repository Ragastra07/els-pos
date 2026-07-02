<?php echo $this->extend('layouts/cashier') ?>

<?php echo $this->section('content') ?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/cashier.css') ?>">

<div class="cashier-grid">
    <section class="order-panel">
        <div class="p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Order</h5>
                    <div class="small text-muted">Mode kasir eksperimental</div>
                </div>
                <div class="text-end">
                    <div class="small text-muted">Total</div>
                    <div class="h4 fw-bold mb-0" id="cart-total">Rp 0</div>
                </div>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger mt-3 mb-0" id="server-alert">
                    <?php echo esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="alert alert-danger mt-3 mb-0 d-none" id="cashier-alert"></div>
        </div>

        <div class="cart-list p-3" id="cart-list">
            <div class="text-center text-muted py-5" id="cart-empty">
                Belum ada item.
            </div>
        </div>

        <div class="p-3 border-top" id="cart-mode">
            <div class="mb-3">
                <label for="customer-name" class="form-label small text-muted">Customer</label>
                <input type="text" class="form-control" id="customer-name" value="Umum">
            </div>

            <div class="d-grid gap-2 mb-3">
                <div class="btn-group">
                    <a href="<?php echo base_url('/products') ?>" class="btn btn-outline-secondary btn-sm">
                        Master Produk
                    </a>
                    <a href="<?php echo base_url('/sales') ?>" class="btn btn-outline-secondary btn-sm">
                        Riwayat Transaksi
                    </a>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm" id="reset-cart">
                    Reset Cart
                </button>
            </div>

            <div class="numpad-grid mb-3" id="cart-numpad">
                <button type="button" class="btn btn-light border" data-key="1">1</button>
                <button type="button" class="btn btn-light border" data-key="2">2</button>
                <button type="button" class="btn btn-light border" data-key="3">3</button>
                <button type="button" class="btn btn-outline-primary" data-action="plus">+</button>
                <button type="button" class="btn btn-light border" data-key="4">4</button>
                <button type="button" class="btn btn-light border" data-key="5">5</button>
                <button type="button" class="btn btn-light border" data-key="6">6</button>
                <button type="button" class="btn btn-outline-primary" data-action="minus">-</button>
                <button type="button" class="btn btn-light border" data-key="7">7</button>
                <button type="button" class="btn btn-light border" data-key="8">8</button>
                <button type="button" class="btn btn-light border" data-key="9">9</button>
                <button type="button" class="btn btn-outline-danger" data-action="remove">Hapus</button>
                <button type="button" class="btn btn-outline-secondary" data-action="clear">Clear</button>
                <button type="button" class="btn btn-light border" data-key="0">0</button>
                <button type="button" class="btn btn-outline-secondary" data-action="backspace">Back</button>
                <button type="button" class="btn btn-primary" id="payment-button">Bayar</button>
            </div>
        </div>

        <div class="p-3 border-top d-none" id="payment-mode">
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <div class="small text-muted">Total</div>
                    <div class="fw-bold" id="payment-total">Rp 0</div>
                </div>
                <div class="col-4">
                    <div class="small text-muted">Bayar</div>
                    <div class="fw-bold" id="paid-display">Rp 0</div>
                </div>
                <div class="col-4">
                    <div class="small text-muted">Kembali</div>
                    <div class="fw-bold" id="change-display">Rp 0</div>
                </div>
            </div>

            <div class="numpad-grid mb-3" id="payment-numpad">
                <button type="button" class="btn btn-light border" data-key="1">1</button>
                <button type="button" class="btn btn-light border" data-key="2">2</button>
                <button type="button" class="btn btn-light border" data-key="3">3</button>
                <button type="button" class="btn btn-outline-secondary" data-action="clear">Clear</button>
                <button type="button" class="btn btn-light border" data-key="4">4</button>
                <button type="button" class="btn btn-light border" data-key="5">5</button>
                <button type="button" class="btn btn-light border" data-key="6">6</button>
                <button type="button" class="btn btn-outline-secondary" data-action="backspace">Back</button>
                <button type="button" class="btn btn-light border" data-key="7">7</button>
                <button type="button" class="btn btn-light border" data-key="8">8</button>
                <button type="button" class="btn btn-light border" data-key="9">9</button>
                <button type="button" class="btn btn-outline-success" id="exact-payment">Uang Pas</button>
                <button type="button" class="btn btn-outline-secondary" id="back-to-cart">Cart</button>
                <button type="button" class="btn btn-light border" data-key="0">0</button>
                <button type="button" class="btn btn-light border" data-key="000">000</button>
                <button type="button" class="btn btn-success" id="complete-payment">Lunas</button>
            </div>
        </div>
    </section>

    <section class="product-panel">
        <div class="p-3 border-bottom">
            <div class="row g-2 align-items-center">
                <div class="col-lg-6">
                    <input
                        type="search"
                        class="form-control"
                        id="product-search"
                        placeholder="Cari produk..."
                        autocomplete="off"
                    >
                </div>
                <div class="col-lg-6">
                    <div class="category-strip text-lg-end" id="category-filter">
                        <button type="button" class="btn btn-primary btn-sm me-1 mb-1" data-category="all">
                            Semua
                        </button>
                        <?php foreach ($categories as $category): ?>
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm me-1 mb-1"
                                data-category="<?php echo esc($category['id'], 'attr') ?>"
                            >
                                <?php echo esc($category['name']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-grid p-3" id="product-grid">
            <!-- Product cards will be dynamically inserted here -->
            <?php if (! empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    $stock = (int) $product['stock'];
                    if ($stock <= 0) {
                        $badgeClass = 'text-bg-secondary';
                        $badgeText  = 'Habis';
                    } elseif ($stock <= 5) {
                        $badgeClass = 'text-bg-warning';
                        $badgeText  = 'Menipis';
                    } else {
                        $badgeClass = 'text-bg-success';
                        $badgeText  = 'Aman';
                    }
                    ?>
                    <button
                        type="button"
                        class="product-card p-3"
                        data-id="<?php echo esc($product['id'], 'attr') ?>"
                        data-name="<?php echo esc($product['name'], 'attr') ?>"
                        data-code="<?php echo esc($product['product_code'], 'attr') ?>"
                        data-category-id="<?php echo esc($product['category_id'], 'attr') ?>"
                        data-category-name="<?php echo esc($product['category_name'] ?? '-', 'attr') ?>"
                        data-price="<?php echo esc($product['selling_price'], 'attr') ?>"
                        data-stock="<?php echo esc($product['stock'], 'attr') ?>"
                        data-search="<?php echo esc(strtolower($product['product_code'] . ' ' . $product['name'] . ' ' . ($product['category_name'] ?? '')), 'attr') ?>"
                        <?php echo $stock <= 0 ? 'disabled' : '' ?>
                    >
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <span class="badge <?php echo esc($badgeClass) ?>"><?php echo esc($badgeText) ?></span>
                            <span class="small text-muted">Stok <?php echo esc($stock) ?></span>
                        </div>
                        <div class="fw-bold mb-1"><?php echo esc($product['name']) ?></div>
                        <div class="small text-muted mb-2">
                            <?php echo esc($product['product_code']) ?> - <?php echo esc($product['category_name'] ?? '-') ?>
                        </div>
                        <div class="fw-bold text-primary">
                            Rp <?php echo number_format((float) $product['selling_price'], 0, ',', '.') ?>
                        </div>
                    </button>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-muted py-5">
                    Belum ada produk tersedia.
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<form action="<?php echo base_url('/sales/store') ?>" method="post" id="cashier-form" class="d-none">
    <?php echo csrf_field() ?>
    <input type="hidden" name="customer_name" id="form-customer-name">
    <input type="hidden" name="paid_amount" id="form-paid-amount">
    <div id="form-items"></div>
</form>

<script src="<?php echo base_url('assets/js/cashier.js') ?>"></script>

<?php echo $this->endSection() ?>
