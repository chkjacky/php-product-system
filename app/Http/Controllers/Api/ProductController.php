<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\GetProductsRequest;
use App\Http\Requests\Products\StoreProductsRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(protected ProductService $ProductService) {}

    public function index(GetProductsRequest $request): JsonResponse
    {
        $products = $this->ProductService->getAllProducts(
            $request->validated(),
        );

        return response()->json([
            "message" => "Products fetched successfully",
            "data" => ProductResource::collection($products),
            "meta" => [
                "per_page" => $products->perPage(),
                "next_cursor" => $products->nextCursor()?->encode(),
                "prev_cursor" => $products->previousCursor()?->encode(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $product = $this->ProductService->getProductById($id);

        return response()->json([
            "message" => "Product fetched successfully",
            "data" => new ProductResource($product),
        ]);
    }

    public function store(StoreProductsRequest $request): JsonResponse
    {
        $product = $this->ProductService->createProduct($request->validated());

        return response()
            ->json([
                "message" => "Product created successfully",
                "data" => new ProductResource($product),
            ])
            ->setStatusCode(201);
    }

    public function update(
        UpdateProductRequest $request,
        Product $product,
    ): JsonResponse {
        $product = $this->ProductService->updateProduct(
            $product,
            $request->validated(),
        );

        return response()->json([
            "message" => "Product updated successfully",
            "data" => new ProductResource($product),
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->ProductService->deleteProduct($product);

        return response()->json([
            "message" => "Product deleted successfully",
        ]);
    }
}
