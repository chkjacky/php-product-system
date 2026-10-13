<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Validation\ValidationException;

class SupplierService
{
    public function getAllSuppliers(array $filters = []): CursorPaginator
    {
        /*
        $query = Supplier::query();

        if (!empty($filters["search"])) {
            $query->where("name", "like", "%" . $filters["search"] . "%");
        }

        if (!empty($filters["is_active"])) {
            $query->active();
        }

        $sort = $filters["sort"] ?? "id";
        $order = $filters["order"] ?? "asc";
        $query->orderBy($sort, $order);

        $perPage = $filters["per_page"] ?? 15;

        return $query->cursorPaginate($perPage);
        */
        $query = Supplier::query()
            ->when(
                ! empty($filters["search"]),
                fn ($q) => $q->search($filters["search"]),
            )
            ->when(
                array_key_exists("is_active", $filters) &&
                    $filters["is_active"] !== null,
                fn ($q) => $q->where(
                    "is_active",
                    filter_var($filters["is_active"], FILTER_VALIDATE_BOOLEAN),
                ),
            );

        return $query->cursorPaginate(15);
    }

    public function getSupplierById(string $id)
    {
        return Supplier::findOrFail($id);
    }

    public function createSupplier(array $data): Supplier
    {
        $existing = Supplier::withTrashed()
            ->where("email", $data["email"])
            ->first();

        if ($existing && $existing->trashed()) {
            $existing->restore();
            $existing->update($data);

            return $existing;
        } else {
            return Supplier::create($data);
        }
    }

    public function updateSupplier(Supplier $supplier, array $data): Supplier
    {
        $result = $supplier->update($data);

        return $supplier->fresh();
    }

    public function deleteSupplier(Supplier $supplier): bool
    {
        if ($supplier->products()->exists()) {
            throw ValidationException::withMessages([
                "supplier" => "This supplier cannot be deleted because it has products.",
            ]);
        }

        return $supplier->delete();
    }
}
