<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\GetSuppliersRequest;
use App\Http\Requests\Suppliers\StoreSuppliersRequest;
use App\Http\Requests\Suppliers\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $SupplierService) {}

    public function index(GetSuppliersRequest $request): JsonResponse
    {
        $suppliers = $this->SupplierService->getAllSuppliers(
            $request->validated(),
        );

        return response()->json([
            "message" => "Suppliers fetched successfully",
            "data" => SupplierResource::collection($suppliers),
            "meta" => [
                "per_page" => $suppliers->perPage(),
                "next_cursor" => $suppliers->nextCursor()?->encode(),
                "prev_cursor" => $suppliers->previousCursor()?->encode(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $supplier = $this->SupplierService->getSupplierById($id);

        return response()->json([
            "message" => "Supplier fetched successfully",
            "data" => new SupplierResource($supplier),
        ]);
    }

    public function store(StoreSuppliersRequest $request): JsonResponse
    {
        $supplier = $this->SupplierService->createSupplier(
            $request->validated(),
        );

        return response()->json([
            "message" => "Supplier created successfully",
            "data" => new SupplierResource($supplier),
        ]);
    }

    public function update(
        UpdateSupplierRequest $request,
        Supplier $supplier,
    ): JsonResponse {
        $supplier = $this->SupplierService->updateSupplier(
            $supplier,
            $request->validated(),
        );

        return response()->json([
            "message" => "Supplier updated successfully",
            "data" => new SupplierResource($supplier),
        ]);
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $this->SupplierService->deleteSupplier($supplier);

        return response()->json([
            "message" => "Supplier deleted successfully",
        ]);
    }
}
