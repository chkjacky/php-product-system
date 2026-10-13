<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $CategoryService) {}

    public function index()
    {
        return response()->json($this->CategoryService->getAllCategories());
    }

    public function show(int $id)
    {
        return response()->json($this->CategoryService->getSupplierById($id));
    }
}
