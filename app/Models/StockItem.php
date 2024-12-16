<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'nama',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}