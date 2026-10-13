<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\GetCategoriesRequest;
use App\Http\Requests\Categories\StoreCategoriesRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $CategoryService) {}

    public function index(GetCategoriesRequest $request): JsonResponse
    {
        $categories = $this->CategoryService->getAllCategories(
            $request->validated(),
        );

        return response()->json([
            "message" => "Categories fetched successfully",
            "data" => CategoryResource::collection($categories),
            "meta" => [
                "per_page" => $categories->perPage(),
                "next_cursor" => $categories->nextCursor()?->encode(),
                "prev_cursor" => $categories->previousCursor()?->encode(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $category = $this->CategoryService->getCategoryById($id);

        return response()->json([
            "message" => "Category fetched successfully",
            "data" => new CategoryResource($category),
        ]);
    }

    public function store(StoreCategoriesRequest $request): JsonResponse
    {
        $category = $this->CategoryService->createCategory(
            $request->validated(),
        );

        return response()
            ->json([
                "message" => "Category created successfully",
                "data" => new CategoryResource($category),
            ])
            ->setStatusCode(201);
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category,
    ): JsonResponse {
        $category = $this->CategoryService->updateCategory(
            $category,
            $request->validated(),
        );

        return response()->json([
            "message" => "Category updated successfully",
            "data" => new CategoryResource($category),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->CategoryService->deleteCategory($category);

        return response()->json([
            "message" => "Category deleted successfully",
        ]);
    }
}
