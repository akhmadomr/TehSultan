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

    // Add status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Add valid statuses array
    public static $validStatuses = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED
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

    // Remove or comment out this line as it might cause conflicts
    // protected $with = ['user', 'outlet', 'stockItem'];

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

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by')
            ->select(['id', 'nama', 'username']); // Explicitly select the fields we need
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Add stockItem relationship
    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
