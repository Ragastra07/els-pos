<!-- Use the main layout as the base template for this page. -->
<?= $this->extend('layouts/main') ?>

<!-- Start content section that will be rendered inside layouts/main.php. -->
<?= $this->section('content') ?>

<!-- Page header. -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-1">Daftar Produk</h4>
        <p class="text-muted mb-0">
            Data produk toko komputer yang digunakan untuk transaksi penjualan.
        </p>
    </div>
</div>

<!-- Product table card. -->
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <!-- table-responsive makes the table scrollable on smaller screens. -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">

                <!-- Table header. -->
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Satuan</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Check if product data exists before rendering table rows. -->
                    <?php if (!empty($products)) : ?>

                        <!-- Manual row number for display only. -->
                        <?php $no = 1; ?>

                        <!-- Loop through product data sent from ProductController. -->
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td><?= $no++ ?></td>

                                <!-- esc() is used to safely display data and prevent unwanted HTML output. -->
                                <td><?= esc($product['product_code']) ?></td>
                                <td><?= esc($product['name']) ?></td>
                                <td><?= esc($product['category_name']) ?></td>

                                <!-- Format selling price into Indonesian Rupiah style. -->
                                <td class="text-end">
                                    Rp <?= number_format($product['selling_price'], 0, ',', '.') ?>
                                </td>

                                <td class="text-center">
                                    <?= esc($product['stock']) ?>
                                </td>

                                <td class="text-center">
                                    <?= esc($product['unit']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else : ?>

                        <!-- Empty state if there is no product data. -->
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada data produk.
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- End content section. -->
<?= $this->endSection() ?>