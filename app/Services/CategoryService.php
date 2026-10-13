<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById(int $id)
    {
        return Category::findOrFail($id);
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(int $id, array $data): Category
    {
        $Category = Category::findOrFail($id);
        $Category->update($data);
        return $Category;
    }

    public function deleteCategory(int $id): bool
    {
        return Category::findOrFail($id)->delete();
    }
}
