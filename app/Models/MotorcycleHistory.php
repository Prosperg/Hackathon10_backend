<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorcycleHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'motorcycle_id',
        'user_id',
        'action',
        'action_time',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'action_time' => 'datetime',
        'metadata' => 'json'
    ];

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
