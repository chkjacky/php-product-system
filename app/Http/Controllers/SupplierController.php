<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $SupplierService) {}

    public function index()
    {
        return response()->json($this->SupplierService->getAllSuppliers());
    }

    public function show(int $id)
    {
        return response()->json($this->SupplierService->getSupplierById($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:Suppliers,email",
        ]);

        return response()->json(
            $this->SupplierService->createSupplier($validated),
            201,
        );
    }
}
