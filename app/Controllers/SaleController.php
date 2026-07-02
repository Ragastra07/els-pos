<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SaleItemModel;
use App\Models\SaleModel;

class SaleController extends BaseController
{
    public function index()
    {
        // Simple route protection to prevent unauthenticated access.
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $saleModel = new SaleModel();

        // Get sales data with cashier/user name.
        $sales = $saleModel
            ->select('sales.*, users.name AS cashier_name')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->orderBy('sales.id', 'DESC')
            ->findAll();

        return view('sales/index', [
            'title' => 'Riwayat Transaksi - ELS POS Simple',
            'sales' => $sales,
        ]);
    }

    // Show the form to create a new sale transaction.
    public function create()
    {
        // Simple route protection to prevent unauthenticated access.
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $productModel = new ProductModel();

        // Only show products that still have stock.
        $products = $productModel
            ->where('stock >', 0)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('sales/create', [
            'title'    => 'Transaksi Penjualan - ELS POS Simple',
            'products' => $products,
        ]);
    }

    // Handle the submission of a new sale transaction.
    public function store()
    {
        // Simple route protection to prevent unauthenticated access.
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $productIds   = $this->request->getPost('product_id');
        $quantities   = $this->request->getPost('qty');
        $customerName = $this->request->getPost('customer_name') ?: 'Umum';
        $paidAmount   = (float) $this->request->getPost('paid_amount');

        if (empty($productIds) || empty($quantities)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Minimal satu produk harus dipilih.');
        }

        $productModel  = new ProductModel();
        $saleModel     = new SaleModel();
        $saleItemModel = new SaleItemModel();

        $items       = [];
        $totalAmount = 0;

        // Validate selected products and calculate transaction total.
        foreach ($productIds as $index => $productId) {
            if (empty($productId)) {
                continue;
            }

            $qty = (int) ($quantities[$index] ?? 0);

            if ($qty <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jumlah produk harus lebih dari 0.');
            }

            $product = $productModel->find($productId);

            if (! $product) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Produk tidak ditemukan.');
            }

            if ($product['stock'] < $qty) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Stok produk ' . $product['name'] . ' tidak mencukupi.');
            }

            $price    = (float) $product['selling_price'];
            $subtotal = $price * $qty;

            $items[] = [
                'product'  => $product,
                'qty'      => $qty,
                'price'    => $price,
                'subtotal' => $subtotal,
            ];

            $totalAmount += $subtotal;
        }

        if (empty($items)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Minimal satu produk harus dipilih.');
        }

        if ($paidAmount < $totalAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nominal pembayaran kurang dari total transaksi.');
        }

        $db = \Config\Database::connect();

        // Use database transaction so all sales data is saved safely.
        // If one process fails, all changes will be rolled back.
        $db->transStart();

        $invoiceNumber = $this->generateInvoiceNumber();

        // Prepare sale data to be saved in the sales table.
        $saleData = [
            'user_id'        => session()->get('user_id'),
            'invoice_number' => $invoiceNumber,
            'customer_name'  => $customerName,
            'total_amount'   => $totalAmount,
            'paid_amount'    => $paidAmount,
            'change_amount'  => $paidAmount - $totalAmount,
            'sale_date'      => date('Y-m-d H:i:s'),
        ];
        // Save the sale transaction and get its ID for saving sale items.
        $saleModel->insert($saleData);
        $saleId = $saleModel->getInsertID();

        // Save each sale item and reduce product stock accordingly.
        foreach ($items as $item) {
            $product = $item['product'];

            $saleItemModel->insert([
                'sale_id'    => $saleId,
                'product_id' => $product['id'],
                'qty'        => $item['qty'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);

            // Reduce product stock after the sale is saved.
            $productModel->update($product['id'], [
                'stock' => $product['stock'] - $item['qty'],
            ]);
        }

        $db->transComplete();

        // Check if the transaction was successful. If not, redirect back with an error message.
        if (! $db->transStatus()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Transaksi gagal disimpan.');
        }

        return redirect()->to('/sales/show/' . $saleId)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    // Show the details of a specific sale transaction.
    public function show($id)
    {
        // Simple route protection to prevent unauthenticated access.
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $saleModel     = new SaleModel();
        $saleItemModel = new SaleItemModel();

        $sale = $saleModel
            ->select('sales.*, users.name AS cashier_name')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->where('sales.id', $id)
            ->first();

        if (! $sale) {
            return redirect()->to('/sales')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $items = $saleItemModel
            ->select('sale_items.*, products.product_code, products.name AS product_name')
            ->join('products', 'products.id = sale_items.product_id', 'left')
            ->where('sale_items.sale_id', $id)
            ->findAll();

        return view('sales/show', [
            'title' => 'Detail Transaksi - ELS POS Simple',
            'sale'  => $sale,
            'items' => $items,
        ]);
    }

    private function generateInvoiceNumber()
    {
        // Generate simple invoice number based on current date and time.
        return 'INV-' . date('Ymd-His');
    }
}
