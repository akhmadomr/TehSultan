<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'stock_item_id',
        'requested_by',
        'validated_by',
        'request_amount',
        'status',
        'validated_at',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'outlet_id' => 'integer',
        'stock_item_id' => 'integer',
        'request_amount' => 'integer',
        'requested_by' => 'integer',
        'validated_by' => 'integer',
        'validated_at' => 'datetime'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Add stockItem relationship
    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
