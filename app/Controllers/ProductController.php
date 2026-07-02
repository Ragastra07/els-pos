<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    public function index()
    {
        // Simple route protection to prevent unauthenticated access.
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $productModel = new ProductModel();

        // Get product data with its category name using a database join.
        $products = $productModel
            ->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->orderBy('products.id', 'ASC')
            ->findAll();

        return view('products/index', [
            'title'    => 'Daftar Produk - ELS POS Simple',
            'products' => $products,
        ]);
    }
}
