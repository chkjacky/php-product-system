<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $ProductService) {}

    public function index()
    {
        return response()->json($this->ProductService->getAllProducts());
    }

    public function show(int $id)
    {
        return response()->json($this->ProductService->getSupplierById($id));
    }
}
