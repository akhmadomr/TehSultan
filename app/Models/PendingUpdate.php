<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingUpdate extends Model
{
    protected $fillable = [
        'user_id',
        'data',
        'type',
        'verification_token',
        'expires_at',
    ];

    protected $casts = [
        'data' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
