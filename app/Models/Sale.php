<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'outlet_id',
        'product_id',
        'quantity',
        'total_price',
        'sale_date',
        'payment_method',
        'status'
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    /**
     * Get the outlet that owns the sale
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get the product that owns the sale
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}