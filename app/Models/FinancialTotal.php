<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialTotal extends Model
{
    protected $fillable = [
        'outlet_id',
        'income',
        'expense',
        'net',
        'total_income',
        'total_expense',
        'total_net'
    ];

    protected $casts = [
        'income' => 'decimal:2',
        'expense' => 'decimal:2',
        'net' => 'decimal:2',
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'total_net' => 'decimal:2'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}