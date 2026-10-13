<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getAllSuppliers()
    {
        return Supplier::all();
    }

    public function getSupplierById(int $id)
    {
        return Supplier::findOrFail($id);
    }

    public function createSupplier(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function updateSupplier(int $id, array $data): Supplier
    {
        $Supplier = Supplier::findOrFail($id);
        $Supplier->update($data);
        return $Supplier;
    }

    public function deleteSupplier(int $id): bool
    {
        return Supplier::findOrFail($id)->delete();
    }
}
