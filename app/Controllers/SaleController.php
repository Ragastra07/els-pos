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
        // Protect this action. Only logged-in users can store sales transactions.
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $productModel  = new ProductModel();
        $saleModel     = new SaleModel();
        $saleItemModel = new SaleItemModel();

        $productIds   = $this->request->getPost('product_id') ?? [];
        $quantities   = $this->request->getPost('qty') ?? [];
        $customerName = trim((string) $this->request->getPost('customer_name'));
        $paidAmount   = (float) $this->request->getPost('paid_amount');

        if ($customerName === '') {
            $customerName = 'Umum';
        }

        if (! is_array($productIds) || ! is_array($quantities)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Data produk tidak valid.');
        }

        $items = [];

        // Build transaction items and merge duplicate products.
        // If the same product is selected more than once, the quantities will be combined first.
        foreach ($productIds as $index => $productId) {
            // Skip empty product IDs (in case of empty selections).
            if ($productId === '' || $productId === null) {
                continue;
            }

            $productId = (int) $productId;
            $qty       = isset($quantities[$index]) ? (int) $quantities[$index] : 0;

            if ($qty <= 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Jumlah produk harus lebih dari 0.');
            }

            $product = $productModel->find($productId);
        
            if (! $product) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Produk tidak ditemukan.');
            }
            // If the product is already in the items array, we will merge the quantities.
            if (! isset($items[$productId])) {
                $items[$productId] = [
                    'product' => $product,
                    'qty'     => 0,
                    'price'   => (float) $product['selling_price'],
                ];
            }
            // Merge quantities for duplicate products.
            $items[$productId]['qty'] += $qty;
        }

        if (empty($items)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pilih minimal satu produk.');
        }

        $totalAmount = 0;

        // Validate stock after duplicate products have been merged.
        foreach ($items as $productId => &$item) {
            $availableStock = (int) $item['product']['stock'];
            $requestedQty   = (int) $item['qty'];
            // Check if the requested quantity exceeds the available stock.
            if ($requestedQty > $availableStock) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Stok produk "' . $item['product']['name'] . '" tidak mencukupi. Stok tersedia: ' . $availableStock . ', jumlah diminta: ' . $requestedQty . '.'
                    );
            }
            // Calculate subtotal for each item and accumulate the total amount.
            $item['subtotal']  = $requestedQty * $item['price'];
            $totalAmount      += $item['subtotal'];
        }

        unset($item);

        if ($paidAmount < $totalAmount) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Nominal pembayaran kurang dari total transaksi.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $invoiceNumber = $this->generateInvoiceNumber();

        $saleData  = [
            'user_id'        => session()->get('user_id'),
            'invoice_number' => $invoiceNumber,
            'customer_name'  => $customerName,
            'total_amount'   => $totalAmount,
            'paid_amount'    => $paidAmount,
            'change_amount'  => $paidAmount - $totalAmount,
            'sale_date'      => date('Y-m-d H:i:s'),
        ];

        $saleModel->insert($saleData);
        $saleId = $saleModel->getInsertID();

        foreach ($items as $productId => $item) {
            $saleItemModel->insert([
                'sale_id'    => $saleId,
                'product_id' => $productId,
                'qty'        => $item['qty'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);

            $newStock = (int) $item['product']['stock'] - (int) $item['qty'];

            $productModel->update($productId, [
                'stock' => $newStock,
            ]);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Transaksi gagal disimpan.');
        }

        return redirect()
            ->to('/sales/show/' . $saleId)
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
