<?php

namespace App\Services;

use App\Models\CashFlow;
use App\Models\Sale;
use App\Traits\CodeGenerator;
use Illuminate\Support\Facades\DB;

class CashFlowService
{
    use CodeGenerator;

    /**
     * Synchronize a Sale with CashFlow.
     * Creates, updates, or deletes the corresponding CashFlow record based on Sale status.
     *
     * @param Sale $sale
     * @return void
     */
    public function syncFromSale(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $existingCashFlow = CashFlow::where('reference_type', Sale::class)
                ->where('reference_id', $sale->id)
                ->first();

            // Determine if we need a cash flow entry and the amount
            $shouldHaveCashFlow = in_array($sale->status, ['paid', 'partial']);
            
            $amount = 0;
            if ($sale->status === 'paid') {
                $amount = $sale->total;
            } elseif ($sale->status === 'partial') {
                // If partial, the cash received is assumed to be the down_payment.
                // If down_payment is 0 but status is partial, it might be an anomaly, but we use it anyway.
                $amount = $sale->down_payment;
            }

            // If amount is 0 or shouldn't have cash flow, remove existing if any
            if (!$shouldHaveCashFlow || $amount <= 0) {
                if ($existingCashFlow) {
                    $dateToRebalance = $existingCashFlow->date;
                    $existingCashFlow->delete();
                    $this->rebalanceFromDate($dateToRebalance);
                }
                return;
            }

            // Otherwise, create or update
            if ($existingCashFlow) {
                // If data changed
                if ($existingCashFlow->amount != $amount || $existingCashFlow->date != $sale->date) {
                    // Rebalance from the earliest date affected
                    $earliestDate = $existingCashFlow->date < $sale->date ? $existingCashFlow->date : $sale->date;
                    
                    $existingCashFlow->amount = $amount;
                    $existingCashFlow->date = $sale->date;
                    $existingCashFlow->description = 'Penjualan Lunas - Inv ' . $sale->invoice_number;
                    if ($sale->status === 'partial') {
                        $existingCashFlow->description = 'DP Penjualan - Inv ' . $sale->invoice_number;
                    }
                    $existingCashFlow->save();

                    $this->rebalanceFromDate($earliestDate);
                }
            } else {
                // Create new
                $code = $this->generateDatedCode(CashFlow::class, 'CF', 'code');
                
                $description = 'Penjualan Lunas - Inv ' . $sale->invoice_number;
                if ($sale->status === 'partial') {
                    $description = 'DP Penjualan - Inv ' . $sale->invoice_number;
                }

                $cf = CashFlow::create([
                    'code' => $code,
                    'date' => $sale->date,
                    'type' => 'in',
                    'description' => $description,
                    'amount' => $amount,
                    'balance' => 0, // Will be calculated in rebalance
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                ]);

                $this->rebalanceFromDate($cf->date);
            }
        });
    }

    /**
     * Delete CashFlow associated with a Sale
     */
    public function deleteForSale(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $existingCashFlow = CashFlow::where('reference_type', Sale::class)
                ->where('reference_id', $sale->id)
                ->first();

            if ($existingCashFlow) {
                $dateToRebalance = $existingCashFlow->date;
                $existingCashFlow->delete();
                $this->rebalanceFromDate($dateToRebalance);
            }
        });
    }

    /**
     * Recalculates all balances starting from a specific date.
     * Orders by date ASC, id ASC.
     *
     * @param string $fromDate
     * @return void
     */
    public function rebalanceFromDate($fromDate)
    {
        // Get the balance immediately before this date
        $previous = CashFlow::where('date', '<', $fromDate)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
            
        $runningBalance = $previous ? (float) $previous->balance : 0;

        // Get all records from this date onwards, ordered properly
        $records = CashFlow::where('date', '>=', $fromDate)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($records as $record) {
            if ($record->type === 'in') {
                $runningBalance += (float) $record->amount;
            } else {
                $runningBalance -= (float) $record->amount;
            }

            // Only update if balance changed to avoid unnecessary DB calls
            if ((float) $record->balance !== $runningBalance) {
                CashFlow::where('id', $record->id)->update(['balance' => $runningBalance]);
            }
        }
    }
}
