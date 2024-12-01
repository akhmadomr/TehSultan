<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $fillable = [
        'outlet_id',
        'report_id',
        'item_name',
        'initial_stock',
        'added_stock',
        'total_stock',
        'remaining_stock',
        'used_stock',
        'status'
    ];

    protected $casts = [
        'initial_stock' => 'integer',
        'added_stock' => 'integer',
        'total_stock' => 'integer',
        'remaining_stock' => 'integer',
        'used_stock' => 'integer'
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    const STATUSES = ['completed', 'pending', 'cancelled'];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}