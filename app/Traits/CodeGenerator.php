<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait CodeGenerator
{
    /**
     * Generate automatic code for a model.
     * 
     * @param string $modelClass The class name of the model.
     * @param string $prefix The prefix for the code (e.g., 'DBR', 'CST').
     * @param string $column The column name to check.
     * @param int $length The length of the numeric part.
     * @return string
     */
    public function generateCode($modelClass, $prefix, $column = 'code', $length = 4)
    {
        $lastRecord = $modelClass::orderBy($column, 'desc')->first();

        if (!$lastRecord) {
            return $prefix . '-' . str_pad(1, $length, '0', STR_PAD_LEFT);
        }

        $lastCode = $lastRecord->$column;
        $lastNumber = (int) str_replace($prefix . '-', '', $lastCode);
        $nextNumber = $lastNumber + 1;

        return $prefix . '-' . str_pad($nextNumber, $length, '0', STR_PAD_LEFT);
    }

    /**
     * Generate automatic invoice number or dated code.
     * 
     * @param string $modelClass
     * @param string $prefix (e.g., 'INV', 'CF')
     * @param string $column
     * @return string
     */
    public function generateDatedCode($modelClass, $prefix, $column = 'invoice_number')
    {
        $datePart = Carbon::now()->format('Ymd');
        $fullPrefix = $prefix . '-' . $datePart . '-';

        $lastRecord = $modelClass::where($column, 'like', $fullPrefix . '%')
            ->orderBy($column, 'desc')
            ->first();

        if (!$lastRecord) {
            return $fullPrefix . '0001';
        }

        $lastCode = $lastRecord->$column;
        $lastNumber = (int) str_replace($fullPrefix, '', $lastCode);
        $nextNumber = $lastNumber + 1;

        return $fullPrefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
