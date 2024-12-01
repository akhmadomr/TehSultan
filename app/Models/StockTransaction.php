<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    protected $fillable = [
        'report_id',
        'product_id',
        'stock_awal',
        'stock_tambah',
        'stock_total',
        'stock_sisa',
        'stock_terpakai',
        'status'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}