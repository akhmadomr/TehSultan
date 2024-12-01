<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    const STATUS_WAITING = 'waiting';
    const STATUS_VALIDATED = 'validated';
    const STATUS_REJECTED = 'rejected';

    const SHIFT_PAGI = 'pagi';
    const SHIFT_SIANG = 'siang';

    protected $fillable = [
        'outlet_id',
        'user_id',
        'type',
        'status',
        'shift'  // Add this
    ];

    protected $attributes = [
        'status' => self::STATUS_WAITING // Set default status
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'shift' => 'string'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}





