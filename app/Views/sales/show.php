<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-1">Detail Transaksi</h4>
        <p class="text-muted mb-0">
            Detail produk yang tercatat dalam transaksi penjualan.
        </p>
    </div>

    <div class="d-flex gap-2">
        <a
            href="<?= base_url('/sales/receipt/' . $sale['id']) ?>"
            class="btn btn-primary btn-sm"
            target="_blank"
        >
            Cetak / Simpan PDF Nota
        </a>
        <a href="<?= base_url('/sales') ?>" class="btn btn-outline-secondary btn-sm">
            Kembali
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="35%">Invoice</th>
                        <td>: <?= esc($sale['invoice_number']) ?></td>
                    </tr>
                    <tr>
                        <th>Customer</th>
                        <td>: <?= esc($sale['customer_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Kasir</th>
                        <td>: <?= esc($sale['cashier_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>: <?= esc($sale['sale_date']) ?></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="35%">Total</th>
                        <td>: Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Bayar</th>
                        <td>: Rp <?= number_format($sale['paid_amount'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Kembalian</th>
                        <td>: Rp <?= number_format($sale['change_amount'], 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)) : ?>
                        <?php $no = 1; ?>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($item['product_code']) ?></td>
                                <td><?= esc($item['product_name']) ?></td>
                                <td class="text-center"><?= esc($item['qty']) ?></td>
                                <td class="text-end">
                                    Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                </td>
                                <td class="text-end">
                                    Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada item transaksi.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
