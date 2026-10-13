<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function getAllCategories(array $filters = []): CursorPaginator
    {
        $query = Category::query();

        if (! empty($filters["search"])) {
            $query->where("name", "like", "%".$filters["search"]."%");
        }

        $sort = $filters["sort"] ?? "id";
        $order = $filters["order"] ?? "asc";
        $query->orderBy($sort, $order);

        $perPage = $filters["per_page"] ?? 15;

        return $query->cursorPaginate($perPage);
    }

    public function getCategoryById(string $id)
    {
        return Category::findOrFail($id);
    }

    public function createCategory(array $data): Category
    {
        // return Category::create($data);

        $existing = Category::withTrashed()
            ->where("name", $data["name"])
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                $existing->fill($data)->save();

                return $existing;
            }

            throw ValidationException::withMessages([
                "name" => "A category with this name already exists.",
            ]);
        }

        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $result = $category->update($data);

        return $category->fresh();
    }

    public function deleteCategory(Category $category): bool
    {
        if ($category->products()->exists()) {
            throw ValidationException::withMessages([
                "category" => "This category cannot be deleted because it has products.",
            ]);
        }

        return $category->delete();
    }
}
