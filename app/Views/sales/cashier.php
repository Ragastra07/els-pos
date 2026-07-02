<?php echo $this->extend('layouts/cashier') ?>

<?php echo $this->section('content') ?>

<style>
    .cashier-grid {
        height: 100%;
        display: grid;
        grid-template-columns: minmax(340px, 38%) minmax(0, 1fr);
        gap: 12px;
        padding: 12px;
    }

    .order-panel,
    .product-panel {
        min-height: 0;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    .order-panel {
        display: flex;
        flex-direction: column;
    }

    .cart-list {
        flex: 1;
        overflow-y: auto;
        min-height: 120px;
    }

    .cart-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: border-color .15s ease, background-color .15s ease;
    }

    .cart-item.active {
        border-color: #0d6efd;
        background: #eef5ff;
    }

    .numpad-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }

    .numpad-grid .btn {
        min-height: 46px;
        font-weight: 700;
    }

    .product-panel {
        display: flex;
        flex-direction: column;
    }

    .category-strip {
        overflow-x: auto;
        white-space: nowrap;
    }

    .product-grid {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 12px;
        align-content: start;
    }

    .product-card {
        min-height: 158px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        text-align: left;
        transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
    }

    .product-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 .35rem 1rem rgba(13, 110, 253, .12);
        transform: translateY(-1px);
    }

    .product-card[disabled] {
        opacity: .55;
        cursor: not-allowed;
        transform: none;
    }

    @media (max-width: 991.98px) {
        body {
            overflow: auto;
        }

        .cashier-shell {
            height: auto;
            overflow: visible;
        }

        .cashier-grid {
            height: auto;
            grid-template-columns: 1fr;
        }

        .order-panel,
        .product-panel {
            min-height: 520px;
        }
    }
</style>

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

<script>
    const cart = new Map();
    let selectedProductId = null;
    let qtyBuffer = '';
    let paidBuffer = '';
    let activeCategory = 'all';

    const cartList = document.getElementById('cart-list');
    const cartEmpty = document.getElementById('cart-empty');
    const cartTotal = document.getElementById('cart-total');
    const cashierAlert = document.getElementById('cashier-alert');
    const cartMode = document.getElementById('cart-mode');
    const paymentMode = document.getElementById('payment-mode');
    const paymentTotal = document.getElementById('payment-total');
    const paidDisplay = document.getElementById('paid-display');
    const changeDisplay = document.getElementById('change-display');

    function formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }

    function showAlert(message) {
        cashierAlert.textContent = message;
        cashierAlert.classList.remove('d-none');
    }

    function clearAlert() {
        cashierAlert.textContent = '';
        cashierAlert.classList.add('d-none');
    }

    function getTotal() {
        let total = 0;

        cart.forEach(function(item) {
            total += item.price * item.qty;
        });

        return total;
    }

    function selectCartItem(productId) {
        selectedProductId = productId;
        qtyBuffer = '';
        renderCart();
    }

    function renderCart() {
        cartList.querySelectorAll('.cart-item').forEach(function(item) {
            item.remove();
        });

        cartEmpty.classList.toggle('d-none', cart.size > 0);

        cart.forEach(function(item) {
            const row = document.createElement('button');
            row.type = 'button';
            row.className = 'cart-item w-100 p-2 mb-2 text-start bg-white';

            if (String(item.id) === String(selectedProductId)) {
                row.classList.add('active');
            }

            row.addEventListener('click', function() {
                selectCartItem(String(item.id));
            });

            const topLine = document.createElement('div');
            topLine.className = 'd-flex justify-content-between gap-2';

            const name = document.createElement('div');
            name.className = 'fw-semibold';
            name.textContent = item.name;

            const subtotal = document.createElement('div');
            subtotal.className = 'fw-bold text-primary';
            subtotal.textContent = formatRupiah(item.price * item.qty);

            const details = document.createElement('div');
            details.className = 'small text-muted';
            details.textContent = item.code + ' - ' + item.qty + ' x ' + formatRupiah(item.price);

            topLine.appendChild(name);
            topLine.appendChild(subtotal);
            row.appendChild(topLine);
            row.appendChild(details);
            cartList.appendChild(row);
        });

        const total = getTotal();
        cartTotal.textContent = formatRupiah(total);
        paymentTotal.textContent = formatRupiah(total);
        updatePaymentSummary();
    }

    function addProduct(card) {
        clearAlert();

        const product = {
            id: String(card.dataset.id),
            name: card.dataset.name,
            code: card.dataset.code,
            price: Number(card.dataset.price || 0),
            stock: Number(card.dataset.stock || 0)
        };

        if (product.stock <= 0) {
            showAlert('Produk ini sedang habis.');
            return;
        }

        const existing = cart.get(product.id);

        if (existing) {
            if (existing.qty >= existing.stock) {
                showAlert('Qty produk "' + existing.name + '" sudah mencapai stok tersedia.');
                selectCartItem(product.id);
                return;
            }

            existing.qty += 1;
        } else {
            product.qty = 1;
            cart.set(product.id, product);
        }

        selectCartItem(product.id);
    }

    function setSelectedQty(qty) {
        if (! selectedProductId || ! cart.has(selectedProductId)) {
            showAlert('Pilih item cart terlebih dahulu.');
            return;
        }

        const item = cart.get(selectedProductId);

        if (qty <= 0) {
            cart.delete(selectedProductId);
            selectedProductId = cart.size ? String(cart.keys().next().value) : null;
            qtyBuffer = '';
            renderCart();
            return;
        }

        if (qty > item.stock) {
            showAlert('Qty melebihi stok tersedia.');
            qty = item.stock;
            qtyBuffer = String(qty);
        }

        item.qty = qty;
        renderCart();
    }

    function handleCartNumpad(target) {
        const key = target.dataset.key;
        const action = target.dataset.action;

        clearAlert();

        if (key !== undefined) {
            if (! selectedProductId) {
                showAlert('Pilih item cart terlebih dahulu.');
                return;
            }

            qtyBuffer += key;
            setSelectedQty(Number(qtyBuffer));
            return;
        }

        if (action === 'plus' && selectedProductId && cart.has(selectedProductId)) {
            const item = cart.get(selectedProductId);
            setSelectedQty(item.qty + 1);
        }

        if (action === 'minus' && selectedProductId && cart.has(selectedProductId)) {
            const item = cart.get(selectedProductId);
            setSelectedQty(item.qty - 1);
        }

        if (action === 'remove' && selectedProductId) {
            cart.delete(selectedProductId);
            selectedProductId = cart.size ? String(cart.keys().next().value) : null;
            qtyBuffer = '';
            renderCart();
        }

        if (action === 'clear') {
            qtyBuffer = '';
        }

        if (action === 'backspace') {
            qtyBuffer = qtyBuffer.slice(0, -1);

            if (qtyBuffer !== '') {
                setSelectedQty(Number(qtyBuffer));
            }
        }
    }

    function updatePaymentSummary() {
        const total = getTotal();
        const paidAmount = Number(paidBuffer || 0);
        const changeAmount = paidAmount - total;

        paidDisplay.textContent = formatRupiah(paidAmount);
        changeDisplay.textContent = formatRupiah(changeAmount);
    }

    function handlePaymentNumpad(target) {
        const key = target.dataset.key;
        const action = target.dataset.action;

        clearAlert();

        if (key !== undefined) {
            paidBuffer += key;
        }

        if (action === 'clear') {
            paidBuffer = '';
        }

        if (action === 'backspace') {
            paidBuffer = paidBuffer.slice(0, -1);
        }

        updatePaymentSummary();
    }

    function showPaymentMode() {
        clearAlert();

        if (cart.size === 0) {
            showAlert('Cart masih kosong.');
            return;
        }

        cartMode.classList.add('d-none');
        paymentMode.classList.remove('d-none');
        updatePaymentSummary();
    }

    function showCartMode() {
        paymentMode.classList.add('d-none');
        cartMode.classList.remove('d-none');
    }

    function submitTransaction() {
        clearAlert();

        const total = getTotal();
        const paidAmount = Number(paidBuffer || 0);

        if (cart.size === 0) {
            showAlert('Cart masih kosong.');
            return;
        }

        if (paidAmount < total) {
            showAlert('Nominal pembayaran kurang dari total transaksi.');
            return;
        }

        const formItems = document.getElementById('form-items');
        formItems.innerHTML = '';

        document.getElementById('form-customer-name').value = document.getElementById('customer-name').value || 'Umum';
        document.getElementById('form-paid-amount').value = paidAmount;

        cart.forEach(function(item) {
            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'product_id[]';
            productInput.value = item.id;

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'qty[]';
            qtyInput.value = item.qty;

            formItems.appendChild(productInput);
            formItems.appendChild(qtyInput);
        });

        document.getElementById('cashier-form').submit();
    }

    function filterProducts() {
        const keyword = document.getElementById('product-search').value.trim().toLowerCase();

        document.querySelectorAll('.product-card').forEach(function(card) {
            const matchesCategory = activeCategory === 'all' || card.dataset.categoryId === activeCategory;
            const matchesSearch = keyword === '' || card.dataset.search.includes(keyword);

            card.classList.toggle('d-none', ! (matchesCategory && matchesSearch));
        });
    }

    function isPaymentModeActive() {
        return ! paymentMode.classList.contains('d-none');
    }

    function isTypingTarget(target) {
        const tagName = target.tagName ? target.tagName.toLowerCase() : '';

        return target.isContentEditable || ['input', 'select', 'textarea'].includes(tagName);
    }

    document.addEventListener('keydown', function(event) {
        if (isTypingTarget(event.target)) {
            return;
        }

        const numberKey = event.key.length === 1 && event.key >= '0' && event.key <= '9'
            ? event.key
            : null;
        const paymentActive = isPaymentModeActive();

        if (numberKey !== null) {
            event.preventDefault();

            if (paymentActive) {
                handlePaymentNumpad({ dataset: { key: numberKey } });
            } else {
                handleCartNumpad({ dataset: { key: numberKey } });
            }

            return;
        }

        if (event.key === 'Backspace') {
            event.preventDefault();

            if (paymentActive) {
                handlePaymentNumpad({ dataset: { action: 'backspace' } });
            } else {
                handleCartNumpad({ dataset: { action: 'backspace' } });
            }

            return;
        }

        if (event.key === 'Delete' || event.key === 'Escape') {
            event.preventDefault();

            if (paymentActive) {
                handlePaymentNumpad({ dataset: { action: 'clear' } });
            } else {
                handleCartNumpad({ dataset: { action: 'clear' } });
            }

            return;
        }

        if (event.key === 'Enter') {
            event.preventDefault();

            if (paymentActive) {
                if (cart.size > 0 && Number(paidBuffer || 0) >= getTotal()) {
                    submitTransaction();
                }
            } else if (cart.size > 0) {
                showPaymentMode();
            }

            return;
        }

        if (! paymentActive && (event.key === '+' || event.code === 'NumpadAdd')) {
            event.preventDefault();
            handleCartNumpad({ dataset: { action: 'plus' } });
            return;
        }

        if (! paymentActive && (event.key === '-' || event.code === 'NumpadSubtract')) {
            event.preventDefault();
            handleCartNumpad({ dataset: { action: 'minus' } });
        }
    });

    document.getElementById('product-grid').addEventListener('click', function(event) {
        const card = event.target.closest('.product-card');

        if (card) {
            addProduct(card);
        }
    });

    document.getElementById('cart-numpad').addEventListener('click', function(event) {
        if (event.target.matches('button')) {
            handleCartNumpad(event.target);
        }
    });

    document.getElementById('payment-numpad').addEventListener('click', function(event) {
        if (event.target.matches('button')) {
            handlePaymentNumpad(event.target);
        }
    });

    document.getElementById('payment-button').addEventListener('click', showPaymentMode);
    document.getElementById('back-to-cart').addEventListener('click', showCartMode);
    document.getElementById('complete-payment').addEventListener('click', submitTransaction);

    document.getElementById('exact-payment').addEventListener('click', function() {
        paidBuffer = String(getTotal());
        updatePaymentSummary();
    });

    document.getElementById('reset-cart').addEventListener('click', function() {
        cart.clear();
        selectedProductId = null;
        qtyBuffer = '';
        paidBuffer = '';
        showCartMode();
        clearAlert();
        renderCart();
    });

    document.getElementById('product-search').addEventListener('input', filterProducts);

    document.getElementById('category-filter').addEventListener('click', function(event) {
        const button = event.target.closest('button[data-category]');

        if (! button) {
            return;
        }

        activeCategory = button.dataset.category;

        document.querySelectorAll('#category-filter button').forEach(function(item) {
            item.classList.remove('btn-primary');
            item.classList.add('btn-outline-primary');
        });

        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-primary');
        filterProducts();
    });

    renderCart();
</script>

<?php echo $this->endSection() ?>
