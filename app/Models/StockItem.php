<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'name',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];
}