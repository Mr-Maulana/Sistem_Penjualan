<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;

class SupplierInfoController extends Controller
{
    public function getInfo($id)
    {
        // Now PK is 'code' (string), so find by code directly
        $supplier = Supplier::where('code', $id)->firstOrFail();
        $prefix = $supplier->product_code ?: 'PRD';

        // Find the highest numbered product for this supplier
        $latestProduct = Product::where('supplier_code', $supplier->code)
            ->where('code', 'like', $prefix . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(code, \"-\", -1) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = 1;
        if ($latestProduct) {
            $parts = explode('-', $latestProduct->code);
            $lastNum = end($parts);
            if (is_numeric($lastNum)) {
                $nextNumber = (int)$lastNum + 1;
            }
        }

        return response()->json([
            'prefix'         => $prefix,
            'next_number'    => $nextNumber,
            'suggested_code' => $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT),
            'code'           => $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT),
        ]);
    }
}
