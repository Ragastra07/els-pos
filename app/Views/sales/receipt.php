<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo esc($title ?? 'Nota Penjualan') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/receipt.css') ?>">
</head>
<body>
<div class="receipt-actions">
    <button type="button" onclick="window.print()">Print / Save PDF</button>
    <a href="<?php echo base_url('/sales/show/' . $sale['id']) ?>">Back to Transaction Detail</a>
    <a href="<?php echo base_url('/sales/cashier') ?>">Back to Cashier Mode</a>
</div>

<main class="receipt">
    <header class="receipt-header">
        <h1>ELS POS Simple</h1>
        <p>Nota Penjualan</p>
    </header>

    <section class="receipt-meta">
        <div>
            <span>Invoice</span>
            <strong><?php echo esc($sale['invoice_number']) ?></strong>
        </div>
        <div>
            <span>Tanggal</span>
            <strong><?php echo esc($sale['sale_date']) ?></strong>
        </div>
        <div>
            <span>Customer</span>
            <strong><?php echo esc($sale['customer_name']) ?></strong>
        </div>
        <div>
            <span>Kasir</span>
            <strong><?php echo esc($sale['cashier_name']) ?></strong>
        </div>
    </section>

    <table class="receipt-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Harga</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (! empty($items)): ?>
                <?php $no = 1; ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td>
                            <strong><?php echo esc($item['product_name']) ?></strong>
                            <span><?php echo esc($item['product_code']) ?></span>
                        </td>
                        <td class="text-center"><?php echo esc($item['qty']) ?></td>
                        <td class="text-end">
                            Rp <?php echo number_format((float) $item['price'], 0, ',', '.') ?>
                        </td>
                        <td class="text-end">
                            Rp <?php echo number_format((float) $item['subtotal'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada item transaksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <section class="receipt-summary">
        <div>
            <span>Total</span>
            <strong>Rp <?php echo number_format((float) $sale['total_amount'], 0, ',', '.') ?></strong>
        </div>
        <div>
            <span>Bayar</span>
            <strong>Rp <?php echo number_format((float) $sale['paid_amount'], 0, ',', '.') ?></strong>
        </div>
        <div>
            <span>Kembalian</span>
            <strong>Rp <?php echo number_format((float) $sale['change_amount'], 0, ',', '.') ?></strong>
        </div>
    </section>

    <footer class="receipt-footer">
        <p>Terima kasih sudah berbelanja.</p>
        <small>Simpan nota ini sebagai bukti transaksi.</small>
    </footer>
</main>
</body>
</html>
