<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialSummary extends Model
{
    public static function getFinancialSummary()
    {
        $income = FinancialItem::where('type', 'income')->sum('total');
        $expense = FinancialItem::where('type', 'expense')->sum('total');
        $netTotal = $income - $expense;

        return [
            'income' => $income,
            'expense' => $expense,
            'netTotal' => $netTotal
        ];
    }
}
