<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getAllProducts()
    {
        return Product::all();
    }

    public function getProductById(int $id)
    {
        return Product::findOrFail($id);
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(int $id, array $data): Product
    {
        $Product = Product::findOrFail($id);
        $Product->update($data);
        return $Product;
    }

    public function deleteProduct(int $id): bool
    {
        return Product::findOrFail($id)->delete();
    }
}
