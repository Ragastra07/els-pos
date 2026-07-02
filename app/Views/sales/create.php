<?php echo $this->extend('layouts/main') ?>

<?php echo $this->section('content') ?>

<!-- Page header. -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-1">Transaksi Penjualan</h4>
        <p class="text-muted mb-0">
            Catat transaksi penjualan produk toko komputer.
        </p>
    </div>

    <a href="<?php echo base_url('/sales') ?>" class="btn btn-outline-secondary btn-sm">
        Riwayat Transaksi
    </a>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?php echo session()->getFlashdata('error') ?>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>
    </div>
<?php endif; ?>

<form action="<?php echo base_url('/sales/store') ?>" method="post">
<!-- CSRF Token -->
<?php echo csrf_field() ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">

            <!-- Customer information section. -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="customer_name" class="form-label">Nama Customer</label>
                    <input
                        type="text"
                        name="customer_name"
                        id="customer_name"
                        class="form-control"
                        value="<?php echo old('customer_name', 'Umum') ?>"
                    >
                </div>
            </div>

            <!-- Product item table. -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="sales-item-table">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th width="12%" class="text-center">Stok</th>
                            <th width="15%" class="text-end">Harga</th>
                            <th width="12%" class="text-center">Qty</th>
                            <th width="15%" class="text-end">Subtotal</th>
                            <th width="8%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="sales-item-row">
                            <td>
                                <select name="product_id[]" class="form-select product-select">
                                    <option value="">Pilih produk</option>
                                    <?php foreach ($products as $product): ?>
                                        <option
                                            value="<?php echo esc($product['id']) ?>"
                                            data-price="<?php echo esc($product['selling_price']) ?>"
                                            data-stock="<?php echo esc($product['stock']) ?>"
                                        >
                                            <?php echo esc($product['product_code']) ?> - <?php echo esc($product['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control text-center stock-display" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control text-end price-display" readonly>
                            </td>
                            <td>
                                <input type="number" name="qty[]" class="form-control text-center qty-input" min="1" value="1">
                            </td>
                            <td>
                                <input type="text" class="form-control text-end subtotal-display" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    X
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm" id="add-row">
                + Tambah Produk
            </button>
        </div>
    </div>

    <!-- Payment summary section. -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="total_amount" class="form-label">Total</label>
                        <input type="text" id="total_amount" class="form-control text-end" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="paid_amount" class="form-label">Bayar</label>
                        <input
                            type="number"
                            name="paid_amount"
                            id="paid_amount"
                            class="form-control text-end"
                            min="0"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="change_amount" class="form-label">Kembalian</label>
                        <input type="text" id="change_amount" class="form-control text-end" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Format number into Indonesian Rupiah style.
    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    // Calculate subtotal for one product row.
    function calculateRow(row) {
        const selectedOption = row.querySelector('.product-select').selectedOptions[0];
        const price = selectedOption ? Number(selectedOption.dataset.price || 0) : 0;
        const stock = selectedOption ? Number(selectedOption.dataset.stock || 0) : 0;
        const qty = Number(row.querySelector('.qty-input').value || 0);
        const subtotal = price * qty;

        row.querySelector('.stock-display').value = stock;
        row.querySelector('.price-display').value = formatRupiah(price);
        row.querySelector('.subtotal-display').value = formatRupiah(subtotal);

        return subtotal;
    }

    // Calculate total transaction and change amount.
    function calculateTotal() {
        let total = 0;

        document.querySelectorAll('.sales-item-row').forEach(function(row) {
            total += calculateRow(row);
        });

        const paidAmount = Number(document.getElementById('paid_amount').value || 0);
        const changeAmount = paidAmount - total;

        document.getElementById('total_amount').value = formatRupiah(total);
        document.getElementById('change_amount').value = formatRupiah(changeAmount);
    }

    // Add new product row by cloning the first row.
    document.getElementById('add-row').addEventListener('click', function() {
        const tableBody = document.querySelector('#sales-item-table tbody');
        const firstRow = document.querySelector('.sales-item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.stock-display').value = '';
        newRow.querySelector('.price-display').value = '';
        newRow.querySelector('.qty-input').value = 1;
        newRow.querySelector('.subtotal-display').value = '';

        tableBody.appendChild(newRow);
        calculateTotal();
    });

    // Handle product, quantity, and remove button changes using event delegation.
    document.addEventListener('change', function(event) {
        if (event.target.classList.contains('product-select') || event.target.classList.contains('qty-input')) {
            calculateTotal();
        }
    });

    document.addEventListener('keyup', function(event) {
        if (event.target.id === 'paid_amount' || event.target.classList.contains('qty-input')) {
            calculateTotal();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-row')) {
            const rows = document.querySelectorAll('.sales-item-row');

            if (rows.length > 1) {
                event.target.closest('tr').remove();
                calculateTotal();
            }
        }
    });

    calculateTotal();
</script>

<?php echo $this->endSection() ?>