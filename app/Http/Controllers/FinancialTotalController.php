<?php

namespace App\Http\Controllers;

use App\Models\FinancialTotal;
use Illuminate\Http\Request;

class FinancialTotalController extends Controller
{
    public function updateTotals($outletId, $income, $expense, $net)
    {
        $lastTotal = FinancialTotal::where('outlet_id', $outletId)
            ->latest()
            ->first();

        $newTotal = FinancialTotal::create([
            'outlet_id' => $outletId,
            'income' => $income,
            'expense' => $expense,
            'net' => $net,
            'total_income' => ($lastTotal ? $lastTotal->total_income : 0) + $income,
            'total_expense' => ($lastTotal ? $lastTotal->total_expense : 0) + $expense,
            'total_net' => ($lastTotal ? $lastTotal->total_net : 0) + $net
        ]);

        return $newTotal;
    }

    public function getLatestTotals($outletId)
    {
        return FinancialTotal::where('outlet_id', $outletId)
            ->latest()
            ->first();
    }
}