<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-1">Riwayat Transaksi</h4>
        <p class="text-muted mb-0">
            Daftar transaksi penjualan yang sudah tersimpan.
        </p>
    </div>

    <a href="<?= base_url('/sales/create') ?>" class="btn btn-primary btn-sm">
        + Transaksi Baru
    </a>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Kasir</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Bayar</th>
                        <th class="text-end">Kembalian</th>
                        <th>Tanggal</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sales)) : ?>
                        <?php $no = 1; ?>
                        <?php foreach ($sales as $sale) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($sale['invoice_number']) ?></td>
                                <td><?= esc($sale['customer_name']) ?></td>
                                <td><?= esc($sale['cashier_name']) ?></td>
                                <td class="text-end">
                                    Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?>
                                </td>
                                <td class="text-end">
                                    Rp <?= number_format($sale['paid_amount'], 0, ',', '.') ?>
                                </td>
                                <td class="text-end">
                                    Rp <?= number_format($sale['change_amount'], 0, ',', '.') ?>
                                </td>
                                <td><?= esc($sale['sale_date']) ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('/sales/show/' . $sale['id']) ?>" class="btn btn-info btn-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>