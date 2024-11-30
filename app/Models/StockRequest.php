<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'product_id',
        'requested_by',
        'validated_by',
        'request_amount',
        'status',
        'validated_at',
    ];
}
