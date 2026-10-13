<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Validation\ValidationException;

class ProductService
{
    public function getAllProducts(array $filters = []): CursorPaginator
    {
        $query = Product::query();

        if (!empty($filters["search"])) {
            $query->where("name", "like", "%" . $filters["search"] . "%");
        }

        $sort = $filters["sort"] ?? "id";
        $order = $filters["order"] ?? "asc";
        $query->orderBy($sort, $order);

        $perPage = $filters["per_page"] ?? 15;

        return $query->cursorPaginate($perPage);
    }

    public function getProductById(string $id)
    {
        return Product::findOrFail($id);
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        $result = $product->update($data);

        return $product->fresh();
    }

    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }
}
